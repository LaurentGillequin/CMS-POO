<?php
namespace Entity;

class ContentEntity{
    private $id;
    private $title;
    private $body;
    private $menu_title;
    private $header_title;
    private $created_at;
    private $update_at;

    public function __construct( mysqli &$db ){
        $this->db = $db;
    }

    /**
     * @param mixed $id
     */
    public function setId( $id ) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle( $title ) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody( $body ) {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getMenutitle() {
        return $this->menu_title;
    }

    /**
     * @param mixed $menu_title
     */
    public function setMenutitle( $menu_title ) {
        $this->menu_title = $menu_title;
    }

    /**
     * @return mixed
     */
    public function getHeadertitle() {
        return $this->header_title;
    }

    /**
     * @param mixed $header_title
     */
    public function setHeadertitle( $header_title ) {
        $this->header_title = $header_title;
    }

    public function save(){
        $sql = "INSERT INTO
                    message
                (
                    `id` , `nom`, `prenom`, `email`, `message`
                ) VALUES (
                    null ,
                    '". $this->db->real_escape_string( $_POST['nom']) ."',
                    '". $this->db->real_escape_string( $_POST['prenom']) ."',
                    '". $this->db->real_escape_string( $_POST['email']) ."',
                    '". $this->db->real_escape_string( $_POST['message']) ."'
                 );
        ";
        $this->db->query( $sql );
        $this->id = $this->db->insert_id;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function hydrate( $id ){
        $sql = "SELECT
                    `id` ,
                    `nom`,
                    `prenom`,
                    `email`,
                    `message`
                FROM
                    `message`
                WHERE
                    `id` = ".(int) $id."
                    ";
        $results = $this->db->query( $sql );
        if( $results === false ){
            return false;
        }
        if( $results->num_rows > 0 ){
            $row = $results->fetch_assoc();
            $this->miniHydrate( $this , $row );
        } else {
            return false;
        }
        return true;
    }

    /**
     * @return array|bool
     */
    public function massHydrate(){
        $sql = "SELECT
                    `id` ,
                    `nom`,
                    `prenom`,
                    `email`,
                    `message`
                FROM
                    `message`
                ";
        $results = $this->db->query( $sql );
        if( $results === false ){
            return false;
        }
        $collection = array();
        $inc = 0;
        while( $row = $results->fetch_assoc()){
            $collection[ $inc ] = new message( $this->db );
            $this->miniHydrate( $collection[ $inc ], $row );
            $inc++;
        }
        return $collection;
    }

    private function miniHydrate( message &$message , array $data){
        $message->setId( $data['id'] );
        $message->setPrenom( $data['prenom'] );
        $message->setNom( $data['nom'] );
        $message->setEmail( $data['email'] );
        $message->setMessage( $data['message'] );
    }

    public function toArray()
    {
        return array(
            'id' => $this->getId() ,
            'nom' => $this->getNom() ,
            'prenom' => $this->getPrenom() ,
            'email' => $this->getEmail() ,
            'message' => $this->getMessage() ,
            );
    }

}