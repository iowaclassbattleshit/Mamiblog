<?php

require_once '../lib/Repository.php';

/**
 * Das UserRepository ist zuständig für alle Zugriffe auf die Tabelle "user".
 *
 * Die Ausführliche Dokumentation zu Repositories findest du in der Repository Klasse.
 */
class UserRepository extends Repository
{
    /**
     * Diese Variable wird von der Klasse Repository verwendet, um generische
     * Funktionen zur Verfügung zu stellen.
     */
    protected $tableName = 'user';

    /**
     * Erstellt einen neuen benutzer mit den gegebenen Werten.
     *
     * Das Passwort wird vor dem ausführen des Queries noch mit dem SHA1
     *  Algorythmus gehashed.
     *
     * @param $firstName Wert für die Spalte firstName
     * @param $lastName Wert für die Spalte lastName
     * @param $email Wert für die Spalte email
     * @param $password Wert für die Spalte password
     *
     * @throws Exception falls das Ausführen des Statements fehlschlägt
     */
    /**
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     * @return bool
     */
    //saves user in database
    public function create($firstName, $lastName, $email, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT );
        $query = "INSERT INTO $this->tableName (firstName, lastName, email, password) VALUES (?, ?, ?, ?)";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ssss', $firstName, $lastName, $email, $password);
        if (!$statement->execute()) {
            return false;
        }
        return $statement->insert_id;
    }

    /**
     * @param $email
     * @param $password
     * @return bool
     */
    //check if login is valid
    public function login($email,$password)
    {
        $query = "SELECT * FROM $this->tableName WHERE email = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('s',$email);
        $statement->execute();

        $result = $statement->get_result();
        $user = $result->fetch_object();

        if($user == null)
        {
            Message::set("login_error", "User not found");
            return false;
        }

        if(password_verify($password, $user->password)){
            $_SESSION['user']= $user;
            return true;
        }
        else{
            Message::set("login_error", "Wrong password");
            return false;
        }

    }
}
