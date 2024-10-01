<?php
    $erros = array(
        0 => "Erro desconhecido.",
        1 => "Parâmetros inválidos para a requisição.",
        2 => "Usuário ou senha incorretos.",
        3 => "Usuário não autenticado.",
        4 => "Ação não definida.",
        5 => "Erro ao adicionar aluno.",
        6 => "Nome inválido.",
        7 => "Sobrenome inválido.",
        8 => "Idade inválida.",
        9 => "Turma inválida.",
        10 => "Erro ao carregar os dados.",
        11 => "Erro ao atualizar nota.",
        12 => "Erro ao excluir aluno.",
    );

    function quebrar_sessao(){
        if(session_status() != 2){
            session_start();
        }
        session_unset();
        session_destroy();
    }

    function gerenciar_erro($codigo, $erros, $redirecionar = false){
        return array(
            "codigo" => $codigo,
            "mensagem" => $erros[$codigo],
            "redirecionar" => $redirecionar
        );
    }

    class crudBlog {
        private $pdo = null; 
    
        private static $crudBlog = null; 
    
        private function __construct($conexao){  
        $this->pdo = $conexao; 
        }  
    
        public static function getInstance($conexao){   
            if (!isset(self::$crudBlog)):    
                self::$crudBlog = new crudBlog($conexao);
            endif;   
            return self::$crudBlog;    
        }

        public function login($usuario,$senha){
            try{   
            $stm = $this->pdo->prepare("SELECT usuarios_codigo, usuarios_email FROM usuarios WHERE usuarios_email=? AND usuarios_senha=? AND usuarios_ativo=1 LIMIT 1");
            $stm->bindValue(1, $usuario);
            $stm->bindValue(2, sha1($senha));
            $stm->execute();   
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return empty($dados) ? false : $dados ;
           }catch(PDOException $erro){
            echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false;
           } 
        }

        public function obter_turmas_por_usuario($usuario){
            try{   
            $stm = $this->pdo->prepare("SELECT * FROM turmas WHERE turmas_usuario=? AND turmas_ativo=1");
            $stm->bindValue(1, $usuario);
            $stm->execute();   
            $dados = $stm->fetchAll(PDO::FETCH_OBJ);
            return $dados;
           }catch(PDOException $erro){
            echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false;
           } 
        }

        public function adicionar_aluno($nome, $sobrenome, $nascimento, $turma){
            try{   
            $stm = $this->pdo->prepare("INSERT INTO alunos VALUES(0,?,?,?,?,NULL,NULL,NULL,1)");
            $stm->bindValue(1, $nome);
            $stm->bindValue(2, $sobrenome);
            $stm->bindValue(3, $nascimento);
            $stm->bindValue(4, $turma);
            $stm->execute();   
            return true;
           }catch(PDOException $erro){
            echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false;
           } 
        }

        public function obter_resultado_consulta($consulta){
            try{   
            $stm = $this->pdo->prepare($consulta);
            $stm->execute();   
            $dados = $stm->fetchAll(PDO::FETCH_OBJ);
            return $dados;
           }catch(PDOException $erro){
            echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false;
           } 
        }

        public function atualizar_nota($aluno, $prova, $nota, $usuario){
            $consulta = "UPDATE alunos SET alunos_nota_" . $prova . "=? WHERE alunos_ativo=1 AND alunos_codigo=? AND alunos_turma IN (SELECT turmas_codigo FROM turmas WHERE turmas_usuario=?) LIMIT 1";
            try{   
            $stm = $this->pdo->prepare($consulta);
            $stm->bindValue(1, $nota);
            $stm->bindValue(2, $aluno);
            $stm->bindValue(3, $usuario);
            $stm->execute();
            return true;
           }catch(PDOException $erro){
            echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false;
           } 
        }

        public function excluir_aluno($codigo, $usuario){
            try{   
            $stm = $this->pdo->prepare("UPDATE alunos SET alunos_ativo=0 WHERE alunos_codigo=? AND alunos_turma IN (SELECT turmas_codigo FROM turmas WHERE turmas_usuario=?) LIMIT 1");
            $stm->bindValue(1, $codigo);
            $stm->bindValue(2, $usuario);
            $stm->execute();
            return true;
           }catch(PDOException $erro){
            echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false;
           } 
        }

        public function check_user_already_in_system($email){
            try{   
            $stm = $this->pdo->prepare("SELECT users_code FROM users WHERE users_active=1 AND users_email=? LIMIT 1");
            $stm->bindValue(1, $email);
            $stm->execute();
            $dados = $stm->fetch(PDO::FETCH_OBJ);            
            return array("result" => !empty($dados));
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false;
           } 
        }

        public function check_valid_sign_up_request($address){
            try{   
            $stm = $this->pdo->prepare("SELECT COUNT(authorization_token_code) AS result FROM authorization_token WHERE authorization_token_ip_address=?");
            $stm->bindValue(1, $address);
            $stm->execute();
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return $dados;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function get_date_time(){
            try{   
                $stm = $this->pdo->prepare("SELECT NOW() AS campo");   
                $stm->execute();   
                $dados = $stm->fetch(PDO::FETCH_OBJ);
                return $dados;   
            }catch(PDOException $erro){   
                //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
            }   
        } 

        public function generate_sign_up_hash($email,$expiration,$address,$type){
            $hash = sha1("authentication". $email . $expiration . $address . $type . rand(0,10000));            
            try{   
            $stm = $this->pdo->prepare("DELETE FROM authorization_token WHERE authorization_token_email=?;
                                        INSERT INTO authorization_token VALUES (0,?,?,?,?,?)");
            $stm->bindValue(1, $email);
            $stm->bindValue(2, $email);
            $stm->bindValue(3, $hash);
            $stm->bindValue(4, $expiration);
            $stm->bindValue(5, $address);
            $stm->bindValue(6, $type);
            $stm->execute();
            return $hash;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function logs($id,$action){
            try{   
            $stm = $this->pdo->prepare("INSERT INTO logs VALUES (0,?,?,NOW(),NOW())");
            $stm->bindValue(1, $id);
            $stm->bindValue(2, $action);
            $stm->execute();   
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function get_valid_authentication_hash($hash){
            try{   
            $stm = $this->pdo->prepare("SELECT authorization_token_email, authorization_token_users_type FROM authorization_token WHERE authorization_token_hash=? AND authorization_token_expiration >= CURRENT_TIMESTAMP LIMIT 1");
            $stm->bindValue(1, $hash);
            $stm->execute();   
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return $dados;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function register_user($email, $type){
            $hash = sha1($email . $type . rand(0,10000));
            try{   
            $stm = $this->pdo->prepare("INSERT INTO users VALUES(0,'',?,?,'',?,NULL,0,0,1)");
            $stm->bindValue(1, $email);
            $stm->bindValue(2, $hash);
            $stm->bindValue(3, $type);
            $stm->execute();   
            return true;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function clear_authentication($hash){
            try{   
            $stm = $this->pdo->prepare("DELETE FROM authorization_token WHERE authorization_token_hash=?");
            $stm->bindValue(1, $hash);
            $stm->execute();   
            return true;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function verify_user($email){
            try{   
            $stm = $this->pdo->prepare("SELECT users_code, users_email FROM users WHERE users_email=? AND users_active=1 ORDER BY users_code DESC LIMIT 1");
            $stm->bindValue(1, $email);
            $stm->execute();   
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return $dados;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function generate_password_recovery($id,$email,$expiration){
            $hash = sha1("authentication". $id . $email . $expiration . rand(0,10000));
            try{   
            $stm = $this->pdo->prepare("DELETE FROM recovery_token WHERE recovery_token_user=? AND recovery_token_email=?;
                                        INSERT INTO recovery_token VALUES (0,?,?,?,?)");
            $stm->bindValue(1, $id);
            $stm->bindValue(2, $email);
            $stm->bindValue(3, $id);
            $stm->bindValue(4, $email);
            $stm->bindValue(5, $hash);
            $stm->bindValue(6, $expiration);
            $stm->execute();   
            return $hash;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }  







        //---------------------------------------------- NEW
               

              

        public function authenticate($id,$expiration){
            $hash = sha1("authentication". $id . $expiration . rand(0,10000));
            try{   
            $stm = $this->pdo->prepare("DELETE FROM authentication_token WHERE authentication_token_user=?;INSERT INTO authentication_token VALUES (0,?,?,?)");
            $stm->bindValue(1, $id);
            $stm->bindValue(2, $id);
            $stm->bindValue(3, $hash);
            $stm->bindValue(4, $expiration);
            $stm->execute();   
            return $hash;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }                

        public function change_user_password($id,$hash){
            try{   
                $stm = $this->pdo->prepare("UPDATE users SET users_password=? WHERE users_code=? AND users_active=1 LIMIT 1");
                $stm->bindValue(1, $hash);
                $stm->bindValue(2, $id);
                $stm->execute();
                return true;
            }catch(PDOException $erro){   
                //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
                return false; 
            }
        }              

        public function remove_recovery_token($hash){
            try{   
            $stm = $this->pdo->prepare("DELETE FROM recovery_token WHERE recovery_token_hash=?");
            $stm->bindValue(1, $hash);
            $stm->execute();   
            return true;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }
        
        public function ip_block($address,$expiration){
            try{   
            $stm = $this->pdo->prepare("INSERT INTO ip_blocker VALUES (0,?,?)");
            $stm->bindValue(1, $address);
            $stm->bindValue(2, $expiration);
            $stm->execute();            
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function check_ip_block($address){
            try{   
            $stm = $this->pdo->prepare("SELECT ip_blocker_code FROM ip_blocker WHERE ip_blocker_address=? AND ip_blocker_expiration>=NOW()");
            $stm->bindValue(1, $address);
            $stm->execute();
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return !empty($dados);  
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function register_associated($name,$email,$cpf,$rg,$birth,$address,$phone,$militar_id,$post){
            $hash = sha1($name . $email . rand(0,10000));
            try{   
            $stm = $this->pdo->prepare("INSERT INTO users VALUES(0,?,?,?,?,?,?,?,?,?,?,NULL,3,NULL,0,1)");
            $stm->bindValue(1, $name);
            $stm->bindValue(2, $email);
            $stm->bindValue(3, $hash);
            $stm->bindValue(4, $cpf);
            $stm->bindValue(5, $rg);
            $stm->bindValue(6, $birth);
            $stm->bindValue(7, $address);
            $stm->bindValue(8, $phone);
            $stm->bindValue(9, $militar_id);
            $stm->bindValue(10, $post);
            $stm->execute();   
            return true;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function check_actual_user_password($id,$hash){
            try{   
            $stm = $this->pdo->prepare("SELECT users_code FROM users WHERE users_code=? AND users_password=? AND users_active=1 LIMIT 1");
            $stm->bindValue(1, $id);
            $stm->bindValue(2, $hash);
            $stm->execute();   
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return $dados ? true : false;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function get_user_data_by_hash($hash){
            try{   
                $stm = $this->pdo->prepare("SELECT recovery_token_user AS users_code, recovery_token_email AS users_email FROM recovery_token WHERE recovery_token_hash=? AND recovery_token_expiration >= CURRENT_TIMESTAMP LIMIT 1");
                $stm->bindValue(1, $hash);
                $stm->execute();   
                $dados = $stm->fetch(PDO::FETCH_OBJ);
                return $dados;
            }catch(PDOException $erro){   
                //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
                return false; 
            } 
        }

        public function get_user_email($id){
            try{   
            $stm = $this->pdo->prepare("SELECT users_email FROM users WHERE users_code=? AND users_code>1 LIMIT 1");
            $stm->bindValue(1, $id);
            $stm->execute();   
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return $dados;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function get_valid_recovery_hash($hash){
            try{   
            $stm = $this->pdo->prepare("SELECT recovery_token_user FROM recovery_token WHERE recovery_token_hash=? AND recovery_token_expiration >= CURRENT_TIMESTAMP LIMIT 1");
            $stm->bindValue(1, $hash);
            $stm->execute();   
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return empty($dados) ? false : true;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        } 

        public function check_authentication($id,$hash){
            try{   
                $stm = $this->pdo->prepare("SELECT authentication_token_code FROM authentication_token WHERE authentication_token_user=? AND authentication_token_hash=? AND authentication_token_expiration >= CURRENT_TIMESTAMP LIMIT 1");
            $stm->bindValue(1, $id);
            $stm->bindValue(2, $hash);
            $stm->execute();   
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return $dados ? true : false;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }
        
        



        // TABLE BASE PAGINATED GETTER

        public function get_data_from_table($selector, $page = 1, $items_per_page = 25){
            try{   
            $stm = $this->pdo->prepare($selector);
            $stm->execute();
            $dados = $stm->fetchAll(PDO::FETCH_OBJ);
            $total_items = count($dados);
            $total_pages = ceil($total_items/$items_per_page);
            $page = $page > $total_pages ? 1 : $page ;
            $previous = $page == 1 ? '' : $page - 1 ;
            $next = $total_pages == 0 ? '' : ( $page == $total_pages ? '' : $page + 1);
            $start_index = $items_per_page * ($page - 1);
            $end_index = (($page * $items_per_page) - 1) <= ($total_items - 1) ? (($page * $items_per_page) - 1) : ($total_items - 1);
            $data = array();
            for($index = $start_index; $index <= $end_index; $index ++){
                array_push($data, $dados[$index]);               
            }   
            return array(
                "total_items" => $total_items,
                "total_pages" => $total_pages,
                "previous" => $previous,
                "next" => $next,
                "actual"=> $page,
                "data" => $data
            );
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }   

        public function get_not_paginated_data($selector){
            try{   
            $stm = $this->pdo->prepare($selector);
            $stm->execute();
            $dados = $stm->fetchAll(PDO::FETCH_OBJ);
            $total_items = count($dados);  
            return array(
                "total_items" => $total_items,
                "data" => $dados
            );
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function get_data_by_code($table, $code, $users_code = null){            
            $selector = "*";
            if($table == 'users'){
                $selector = "users_code, users_name, users_email, users_phone, users_type, users_picture, users_notification_email";
            } else if($table == 'systems'){
                $selector = "systems_address, systems_hash, systems_actual_value, systems_backup_day, systems_backup_hour, systems_backup_minute, systems_code, systems_domain_request, systems_domain_valid_date, systems_admin, systems_type, systems_user " . ($users_code != null ? ", IF(systems_admin=" . $users_code . ",1,0) AS user_admin" : "");
            } else if($table == 'portions'){
                $selector = "*" . ($users_code != null ? ", IF((SELECT systems_admin FROM systems WHERE systems_hash=portions_systems_hash LIMIT 1)=" . $users_code . ",1,0) AS user_admin" : "");
            }
            
            try{   
            $stm = $this->pdo->prepare("SELECT " . $selector . " FROM " . $table . " WHERE " . $table . "_code=" . $code . " LIMIT 1");
            $stm->execute(); 
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return $dados;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        // FUNCTIONS TO WRITE DATA

        public function change_user_data($code,$rg,$birth,$address,$phone,$militar_id,$post,$oab){        
            try{   
            $stm = $this->pdo->prepare("UPDATE users SET users_rg=?, users_birth=?, users_address=?, users_phone=?, users_militar_id=?, users_post=?, users_oab=? WHERE users_code=? LIMIT 1");
            $stm->bindValue(1, $rg);
            $stm->bindValue(2, $birth);
            $stm->bindValue(3, $address);
            $stm->bindValue(4, $phone);
            $stm->bindValue(5, $militar_id);
            $stm->bindValue(6, $post);
            $stm->bindValue(7, $oab);
            $stm->bindValue(8, $code);
            $stm->execute();   
            return true;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function get_picture($user_code){
            try{   
            $stm = $this->pdo->prepare("SELECT users_picture FROM users WHERE users_code=? LIMIT 1");
            $stm->bindValue(1, $user_code);
            $stm->execute();   
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return $dados;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function save_picture($user_code,$picture){
            /*if(!$picture){
                $picture = "NULL";
            }*/
            try{   
            $stm = $this->pdo->prepare("UPDATE users SET users_picture=? WHERE users_code=? LIMIT 1");
            $stm->bindValue(1, $picture);
            $stm->bindValue(2, $user_code);
            $stm->execute();   
            return true;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function update_system_info($systems_code,$type,$value){
            try{   
            $stm = $this->pdo->prepare("UPDATE systems SET " . $type . "=?" . ($type == "systems_domain_valid_date" ? ", systems_domain_request=0" : "") . " WHERE systems_code=? LIMIT 1");
            $stm->bindValue(1, $value);
            $stm->bindValue(2, $systems_code);
            $stm->execute();   
            return true;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false;
           } 
        }

        public function change_user_notifications_allow($user_code,$status){
            try{   
            $stm = $this->pdo->prepare("UPDATE users SET users_notification_email=? WHERE users_code=? LIMIT 1");
            $stm->bindValue(1, $status);
            $stm->bindValue(2, $user_code);
            $stm->execute();   
            return true;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function get_user_new_notifications($user_code){
            try{   
            $stm = $this->pdo->prepare("SELECT IF(COUNT(notifications_code) > 0, 'true', 'false') AS result FROM notifications WHERE notifications_user=? AND notifications_active=1 AND notifications_viewed=0");
            $stm->bindValue(1, $user_code);
            $stm->execute();            
            $dados = $stm->fetch(PDO::FETCH_OBJ);
            return $dados;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function set_user_view_notifications($user_code){
            try{   
            $stm = $this->pdo->prepare("UPDATE notifications SET notifications_viewed=1 WHERE notifications_user=? AND notifications_active=1 AND notifications_viewed=0");
            $stm->bindValue(1, $user_code);
            $stm->execute();            
            return true;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }

        public function get_user_notifications($user_code){
            try{   
            $stm = $this->pdo->prepare("SELECT notifications_title, notifications_body, notifications_datetime FROM notifications WHERE notifications_user=? AND notifications_active=1 ORDER BY notifications_datetime DESC, notifications_code DESC  LIMIT 5");
            $stm->bindValue(1, $user_code);
            $stm->execute();   
            $dados = $stm->fetchAll(PDO::FETCH_OBJ);
            return $dados;
           }catch(PDOException $erro){   
            //echo "<script type='text/javascript'>alert('Erro na linha: {$erro->getLine()}')</script>";
            return false; 
           } 
        }
    }
?>