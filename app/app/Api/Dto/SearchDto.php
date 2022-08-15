<?php


namespace App\Api\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class SearchDto extends AbstractDto
{
    /** @var mixed  */
    public mixed $offset;

    /** @var mixed  */
    public mixed $limit;
}
