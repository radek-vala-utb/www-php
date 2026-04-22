<?php

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(string $path): PDO
    {
        if (self::$connection === null) {
            self::$connection = new PDO('sqlite:' . $path);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$connection;
    }
}

abstract class Model
{
    protected static string $table = '';
    protected static array $fillable = [];
    protected static ?PDO $connection = null;

    public ?int $id = null;

    public static function setConnection(PDO $connection): void
    {
        static::$connection = $connection;
    }

    public static function all(string $orderBy = 'id DESC'): array
    {
        $statement = static::$connection->query(
            'SELECT * FROM ' . static::$table . ' ORDER BY ' . $orderBy
        );

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn (array $row) => static::hydrate($row), $rows);
    }

    public static function find(int $id): ?static
    {
        $statement = static::$connection->prepare(
            'SELECT * FROM ' . static::$table . ' WHERE id = :id LIMIT 1'
        );
        $statement->execute([':id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? static::hydrate($row) : null;
    }

    public static function create(array $attributes): static
    {
        $instance = new static();
        $instance->fill($attributes);
        $instance->save();
        return $instance;
    }

    public function fill(array $attributes): void
    {
        foreach (static::$fillable as $field) {
            if (array_key_exists($field, $attributes)) {
                $this->{$field} = $attributes[$field];
            }
        }
    }

    public function save(): void
    {
        $data = $this->toDatabaseArray();

        if ($this->id === null) {
            $columns = implode(', ', array_keys($data));
            $params = implode(', ', array_map(fn (string $column) => ':' . $column, array_keys($data)));

            $statement = static::$connection->prepare(
                'INSERT INTO ' . static::$table . ' (' . $columns . ') VALUES (' . $params . ')'
            );
            $statement->execute($this->prefixKeys($data));
            $this->id = (int) static::$connection->lastInsertId();
            return;
        }

        $setClause = implode(', ', array_map(fn (string $column) => $column . ' = :' . $column, array_keys($data)));
        $data['id'] = $this->id;

        $statement = static::$connection->prepare(
            'UPDATE ' . static::$table . ' SET ' . $setClause . ' WHERE id = :id'
        );
        $statement->execute($this->prefixKeys($data));
    }

    public function delete(): void
    {
        if ($this->id === null) {
            return;
        }

        $statement = static::$connection->prepare(
            'DELETE FROM ' . static::$table . ' WHERE id = :id'
        );
        $statement->execute([':id' => $this->id]);
        $this->id = null;
    }

    protected static function hydrate(array $row): static
    {
        $instance = new static();
        foreach ($row as $field => $value) {
            $instance->{$field} = $value;
        }
        if (isset($row['id'])) {
            $instance->id = (int) $row['id'];
        }
        return $instance;
    }

    protected function prefixKeys(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[':' . $key] = $value;
        }
        return $result;
    }

    abstract protected function toDatabaseArray(): array;
}

final class Course extends Model
{
    protected static string $table = 'courses';
    protected static array $fillable = ['title', 'level'];

    public string $title = '';
    public string $level = '';

    protected function toDatabaseArray(): array
    {
        return [
            'title' => $this->title,
            'level' => $this->level,
        ];
    }
}

final class Student extends Model
{
    protected static string $table = 'students';
    protected static array $fillable = ['name', 'email', 'points', 'course_id'];

    public string $name = '';
    public string $email = '';
    public int $points = 0;
    public int $course_id = 0;

    public function course(): ?Course
    {
        return Course::find($this->course_id);
    }

    protected function toDatabaseArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'points' => $this->points,
            'course_id' => $this->course_id,
        ];
    }
}
