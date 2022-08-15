<?php


namespace App\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class PaginationDto extends AbstractDto
{
    public mixed $offset = 0;
    public mixed $limit = 10;
}
