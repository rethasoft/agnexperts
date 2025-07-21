<?php

namespace App\Domain\Events\DTOs;

use App\Domain\Events\Enums\EventType;
use DateTime;
use Illuminate\Validation\Rule;
use App\Domain\Events\Enums\EventStatus;

class EventData
{
    public function __construct(
        public readonly ?int $employee_id,
        public readonly DateTime $start_date,
        public readonly DateTime $end_date,
        public readonly EventType $type,
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?int $eventable_id = null,
        public readonly ?string $eventable_type = null,
        public readonly string $status = 'scheduled',
        public readonly bool $is_available = false,
        public readonly bool $is_all_day = false,
        public readonly ?array $meta = null,
        public readonly ?int $cancelled_by = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            employee_id: $data['employee_id'] ?? null,
            start_date: new DateTime($data['start_date']),
            end_date: new DateTime($data['end_date']),
            type: EventType::from($data['type']),
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            eventable_id: $data['eventable_id'] ?? null,
            eventable_type: $data['eventable_type'] ?? null,
            status: $data['status'] ?? 'scheduled',
            is_available: $data['is_available'] ?? false,
            is_all_day: $data['is_all_day'] ?? false,
            meta: $data['meta'] ?? null,
            cancelled_by: $data['cancelled_by'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'employee_id' => $this->employee_id,
            'start_date' => $this->start_date->format('Y-m-d H:i:s'),
            'end_date' => $this->end_date->format('Y-m-d H:i:s'),
            'type' => $this->type->value,
            'title' => $this->title,
            'description' => $this->description,
            'eventable_id' => $this->eventable_id,
            'eventable_type' => $this->eventable_type,
            'status' => $this->status,
            'is_available' => $this->is_available,
            'is_all_day' => $this->is_all_day,
            'meta' => $this->meta,
            'cancelled_by' => $this->cancelled_by,
        ];
    }

    public static function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d H:i:s', 'after_or_equal:now'],
            'end_date' => ['required', 'date', 'date_format:Y-m-d H:i:s', 'after:start_date'],
            'type' => ['required', 'string', 'in:' . implode(',', array_column(EventType::cases(), 'value'))],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'eventable_id' => ['nullable', 'integer'],
            'eventable_type' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(EventStatus::class)],
            'is_available' => ['boolean'],
            'is_all_day' => ['boolean'],
            'meta' => ['nullable', 'array'],
            'cancelled_by' => ['nullable', 'exists:users,id'],
        ];
    }
}
