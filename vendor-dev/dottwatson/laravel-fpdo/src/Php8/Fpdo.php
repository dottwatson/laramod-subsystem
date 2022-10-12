<?php
namespace Dottwatson\Fpdo\Php8;

use PDO;
use Vimeo\MysqlEngine\FakePdoInterface;
use Dottwatson\Fpdo\FpdoTrait;

class Fpdo extends PDO implements FakePdoInterface
{
    use FpdoTrait;

    /**
     * @param string $statement
     * @param array $options
     * @return FakePdoStatement
     */
    public function prepare($statement, array $options = [])
    {
        return new FpdoStatement($this, $statement, $this->real);
    }

    /**
     * @param string $statement
     * @param int|null $mode
     * @param mixed ...$fetchModeArgs
     * @return FakePdoStatement
     */
    public function query(string $statement, ?int $mode = PDO::ATTR_DEFAULT_FETCH_MODE, mixed ...$fetchModeArgs)
    {
        $sth = $this->prepare($statement);
        $sth->execute();
        return $sth;
    }
}
