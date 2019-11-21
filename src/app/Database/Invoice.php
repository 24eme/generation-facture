<?php

namespace App\Database;

use App\Database\Connector\DatabaseInterface;

class Invoice
{
    /** @var \PDO $database PDO object */
    protected $database;

    /**
     * Constructor
     *
     * @param string $database_path Path to DB file
     */
    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    /**
     * Retrieve the invoice number
     *
     * @return int The invoice number
     */
    public function getInvoiceNumber()
    {
        $stmt = $this->database->query('SELECT invoice_number FROM invoice_number');
        return $stmt->fetchColumn();
    }

    /**
     * Set the invoice number
     *
     * @param int $value The new value
     * @return bool Update status
     */
    public function setInvoiceNumber(int $value)
    {
        $number = filter_var($value, FILTER_SANITIZE_NUMBER_INT | FILTER_VALIDATE_INT);

        $stmt = $this->database->exec('UPDATE invoice_number SET invoice_number = :number');
        $stmt->bindParam(':number', $number, \PDO::PARAM_INT);

        return $stmt->execute();
    }
}
