<?php

namespace Steamy\Model;

use Steamy\Core\Model;

class District
{
    use Model;

    protected string $table = 'district';

    private int $district_id;
    private string $name;

    public function __construct(int $id)
    {
        $record = $this->first(
            (array)[
                'district_id' => $id,
            ]
        );

        $this->district_id = $record->district_id ?? -1;
        $this->name = $record->name ?? "";
    }

    // NOTE: No setters required for this class as districts are constants.
    public function getName(): string
    {
        return $this->name;
    }
}