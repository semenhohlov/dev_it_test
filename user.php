<?php

class c_user{
    var $db;
    var $url;
    var $table = '';
    var $user_id = 0;
    var $user_login = '';
    var $user_password = '';
    var $user_name = 'Гость';
    var $user_surname = '';
    var $user_last_name = '';
    var $user_email = '';
    var $user_phone = '';
    var $user_avatar = '';
    var $user_priv = 0;
    var $user_ban;
    var $ban_exp;
    var $error = '';
    var $output = '';
    var $user_area = '';
    var $menu = array();

    function __construct($db, $table){
        $this->url = $_SERVER['PHP_SELF'];
        $this->db = $db;
        $this->table = $table;
        //ban expired
        $this->db->update($this->table, "user_ban = 0, ban_exp = 0", "ban_exp < now()");
        $this->error .= $this->db->error;
        if(isset($_COOKIE['user_login']) && (isset($_COOKIE['user_hash']) && isset($_SESSION['user_hash']))){
            $cul = $_COOKIE['user_login'];
            $cuh = $_COOKIE['user_hash'];
            $suh = $_SESSION['user_hash'];
            if(strcmp($cuh, $suh) == 0){
                //loading user form db
                $this->db->select(' * ', $this->table, "user_login = '$cul'");
                if($this->db->num_rows()){
                    $row = $this->db->fetch(MYSQLI_ASSOC);
                    $this->user_id = $row['user_id'];
                    $this->user_login = $row['user_login'];
                    $this->user_password = $row['user_password'];
                    $this->user_name = $row['user_name'];
                    $this->user_surname = $row['user_surname'];
                    $this->user_last_name = $row['user_last_name'];
                    $this->user_email = $row['user_email'];
                    $this->user_phone = $row['user_phone'];
                    $this->user_avatar = $row['user_avatar'];
                    $this->user_priv = $row['user_priv'];
                    $this->user_ban = $row['user_ban'];
                    $this->ban_exp = $row['ban_exp'];
                } else {
                    $this->error .= 'USER: Ошибка загрузки пользователя из БД.<br>';
                }
                $this->db->free();
            }
        }
        //menu items
        if($this->user_id){
            $this->menu['Кабинет'] = "$this->url?action=edit_profile";
        }
    } // __constructor

    function user_area(){
        // user area
        if($this->user_id){
            $this->user_area = "
            <div class=\"user-area\">
                <div class=\"user-avatar center\">
                <img src=\"image.php?uid=$this->user_id\"></div>
                <div class=\"user-name\">Добро пожаловать, $this->user_name $this->user_last_name.</div>
                <div class=\"user-bar\">
                    <a id=\"log-off\" class=\"log-off\" href=\"$this->url?action=log_off\">Выход</a>
                </div>
            </div>
            ";
        } else { //guest
            $this->user_area = "
            <div class=\"user-area\">
                <div class=\"user-avatar center\"><img src=\"image.php\"></div>
                <div class=\"user-name\">Добро пожаловать, $this->user_name.</div>
                <div class=\"user-bar\">
                    <a id=\"login\" class=\"login\" href=\"$this->url?action=login\">Вход</a>
                    <a id=\"register\" class=\"register\" href=\"$this->url?action=register_form\">Регистрация</a>
                </div>
            </div>
            ";
        }
    }

    function login_form(){
        $this->output = "
        <div class=\"login-form center\">
            <form method=\"post\" action=\"$this->url\">
                <input type=\"hidden\" name=\"action\" value=\"auth\">
                <h3 class=\"center\">Авторизация</h3>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_login\">Логин:</label>
                    </div>
                    <div class=\"col-75\">
                        <input id=\"user_login\" type=\"tetx\" name=\"user_login\" value=\"\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_password\">Пароль:</label>
                    </div>
                    <div class=\"col-75\">
                        <input  id=\"user_password\" type=\"password\" name=\"user_password\" value=\"\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <input class=\"center\" type=\"submit\" value=\"Вход\">
                    </div>
                    <div class=\"col-75\"></div>
                </div>
                <div class=\"clear\"></div>
            </form>
        </div>
        ";
    }

    function register_form(){
        $this->output = "
        <div class=\"register-form center\">
            <form method=\"post\" action=\"$this->url\" enctype=\"multipart/form-data\">
                <input type=\"hidden\" name=\"action\" value=\"add_user\">
                <h3>Регистрация</h3>
                <p>Заполните все поля со звездочкой.</p>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_login\">Логин: *</label>
                    </div>
                    <div class=\"col-75\">
                        <input id=\"user_login\" type=\"tetx\" name=\"user_login\" value=\"\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_password_1\">Пароль: *</label>
                    </div>
                    <div class=\"col-75\">
                        <input  id=\"user_password_1\" type=\"password\" name=\"user_password_1\" value=\"\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_password_2\">Пароль: *</label>
                    </div>
                    <div class=\"col-75\">
                        <input  id=\"user_password_2\" type=\"password\" name=\"user_password_2\" value=\"\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_surname\">Фамилия:</label>
                    </div>
                    <div class=\"col-75\">
                        <input type=\"tetx\" name=\"user_surname\" value=\"\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_name\">Имя:</label>
                    </div>
                    <div class=\"col-75\">
                        <input type=\"tetx\" name=\"user_name\" value=\"\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_last_name\">Отчество:</label>
                    </div>
                    <div class=\"col-75\">
                        <input type=\"tetx\" name=\"user_last_name\" value=\"\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_email\">E-mail:</label>
                    </div>
                    <div class=\"col-75\">
                        <input id=\"user_email\" type=\"tetx\" name=\"user_email\" placeholder=\"some.name@suite.com\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_phone\">Телефон:</label>
                    </div>
                    <div class=\"col-75\">
                        <input type=\"tel\" name=\"user_phone\" value=\"\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_avatar\">Аватарка:</label>
                    </div>
                    <div class=\"col-75\">
                        <input type=\"file\" name=\"user_avatar\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <input type=\"submit\" value=\"Сохранить\">
                    </div>
                    <div class=\"col-75\"></div>
                </div>
                <div class=\"clear\"></div>
                <div class=\"notes\">
                    После загрузки аватарка уменьшится до 100 пикселей в ширину.
                </div>
                <div id=\"errors_reg_form\" class=\"request-error\"></div>
            </form>
        </div>
        ";
    }

    function auth(){
        if(!$this->user_id){
            $user_login = $_REQUEST['user_login'];
            $user_password = $_REQUEST['user_password'];
            if($user_login && $user_password){
                $this->db->select(' * ', $this->table, "user_login = '".$user_login."'");
                if($this->db->num_rows()){
                    $row = $this->db->fetch(MYSQLI_ASSOC);
                    if(strcmp($row['user_password'], md5($user_password)) == 0){
                        // correct login password
                        // ban?
                        if($row['user_ban']){
                            $this->error .= "USER: К сожалению Ваш профиль заблокирован.<br>";
                            $this->error .= "Повторите попытку позже или свяжитесь с администрацией сайта.<br>";
                            return false;
                        }
                        //$this->user_id = $row['user_id'];
                        //$this->user_login = $row['user_login'];
                        //$this->user_password = $row['user_password'];
                        //$this->user_name = $row['user_name'];
                        //$this->user_surname = $row['user_surname'];
                        //$this->user_last_name = $row['user_last_name'];
                        //$this->user_email = $row['user_email'];
                        //$this->user_phone = $row['user_phone'];
                        //$this->user_avatar = $row['user_avatar'];
                        //$this->user_priv = $row['user_priv'];
                        //$this->user_ban = $row['user_ban'];
                        //$this->ban_exp = $row['ban_exp'];
                        return true;
                    } else {
                        $this->error .= "USER: Неверный логин или пароль.<br>";
                    }
                } else {
                    $this->error .= "USER: Неверный логин или пароль.<br>";
                }
                $this->db->free();
            } else {
                $this->error .= "USER: Пустой логин или пароль.<br>";
            }
        } else {
            $this->error .= "USER: Повторная авторизация.<br>";
            return true;
        }
        return false;
    }

    function add_user(){
        $user_login = htmlspecialchars($_REQUEST['user_login']);
        $user_password_1 = htmlspecialchars($_REQUEST['user_password_1']);
        $user_password_2 = htmlspecialchars($_REQUEST['user_password_2']);
        $user_name = htmlspecialchars($_REQUEST['user_name']);
        $user_surname = htmlspecialchars($_REQUEST['user_surname']);
        $user_last_name = htmlspecialchars($_REQUEST['user_last_name']);
        $user_email = htmlspecialchars($_REQUEST['user_email']);
        $user_phone = htmlspecialchars($_REQUEST['user_phone']);
        $user_avatar = '';
        $file_name = $_FILES['user_avatar']['name'];
        $file_path = $_FILES['user_avatar']['tmp_name'];

        if($file_name){
            $user_avatar = file_get_contents($file_path);
            $user_avatar = base64_encode($this->resize_image($user_avatar));
        }

        //checks
        if(!$user_login){
            $this->error .= "USER: Пожалуйста заполните все поля со звездочкой. Логин<br>";
            return false;
        }
        if(strlen($user_login) < 5){
            $this->error .= "USER: Слишком короткий логин.<br>";
            $this->error .= "Минимум 5 символов.<br>";
            return false;
        }
        if(!$user_password_1){
            $this->error .= "USER: Пожалуйста заполните все поля со звездочкой. Пароль1<br>";
            return false;
        }
        if(!$user_password_2){
            $this->error .= "USER: Пожалуйста заполните все поля со звездочкой. Пароль2<br>";
            return false;
        }
        if(strcmp($user_password_1, $user_password_2) != 0){
            $this->error .= "USER: Введенные пароли не совпадают.<br>";
            return false;
        }
        $this->db->select(' user_login ', $this->table, "user_login = '".$user_login."'");
        $row = $this->db->fetch(MYSQLI_ASSOC);
        if(isset($row['user_login'])){
            $this->error .= "USER: Данный логин уже занят, выберите другой.<br>";
            return false;
        }
        $this->db->free();
        if($user_email && (!filter_var($user_email, FILTER_VALIDATE_EMAIL))){
            $this->error .= "USER: Некорректный e-mail.<br>";
            return false;
        }
        $user_password = md5($user_password_1);
        $this->db->insert($this->table, "null, 
        '$user_login', 
        '$user_password', 
        '$user_name', 
        '$user_surname', 
        '$user_last_name', 
        '$user_email', 
        '$user_phone',
        '$user_avatar', 0, 0, 0");
        if($this->db->error){
            return false;
        }
        // all ok!
        return true;
    }

    function edit_form(){
        $this->output = "
        <div class=\"register-form center\">
            <h3>Ваши данные</h3>
            <div id=\"errors_reg_form\" class=\"request-error\"></div>
            <form method=\"post\" action=\"$this->url\">
                <input type=\"hidden\" name=\"action\" value=\"update_profile\">
                <input type=\"hidden\" name=\"update\" value=\"login\">
                <h5 class=\"center\">Изменить логин</h5>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_login\">Логин:</label>
                    </div>
                    <div class=\"col-75\">
                        <input id=\"user_login\" type=\"tetx\" name=\"user_login\" value=\"$this->user_login\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <input type=\"submit\" value=\"Сохранить\">
                    </div>
                    <div class=\"col-75\"></div>
                </div>
                <div class=\"clear\"></div>
            </form>
            <hr>
            <form method=\"post\" action=\"$this->url\">
                <input type=\"hidden\" name=\"action\" value=\"update_profile\">
                <input type=\"hidden\" name=\"update\" value=\"password\">
                <h5 class=\"center\">Изменить пароль</h5>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_password_1\">Пароль:</label>
                    </div>
                    <div class=\"col-75\">
                        <input  id=\"user_password_1\" type=\"password\" name=\"user_password_1\" value=\"\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_password_2\">Пароль:</label>
                    </div>
                    <div class=\"col-75\">
                        <input  id=\"user_password_2\" type=\"password\" name=\"user_password_2\" value=\"\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <input type=\"submit\" value=\"Сохранить\">
                    </div>
                    <div class=\"col-75\"></div>
                </div>
                <div class=\"clear\"></div>
            </form>
            <hr>
            <form method=\"post\" action=\"$this->url\">
                <input type=\"hidden\" name=\"action\" value=\"update_profile\">
                <input type=\"hidden\" name=\"update\" value=\"user_data\">
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_surname\">Фамилия:</label>
                    </div>
                    <div class=\"col-75\">
                        <input type=\"tetx\" name=\"user_surname\" value=\"$this->user_surname\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_name\">Имя:</label>
                    </div>
                    <div class=\"col-75\">
                        <input type=\"tetx\" name=\"user_name\" value=\"$this->user_name\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_last_name\">Отчество:</label>
                    </div>
                    <div class=\"col-75\">
                        <input type=\"tetx\" name=\"user_last_name\" value=\"$this->user_last_name\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_email\">E-mail:</label>
                    </div>
                    <div class=\"col-75\">
                        <input id=\"user_email\" type=\"tetx\" name=\"user_email\" placeholder=\"some.name@suite.com\" value=\"$this->user_email\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_phone\">Телефон:</label>
                    </div>
                    <div class=\"col-75\">
                        <input type=\"tel\" name=\"user_phone\" value=\"$this->user_phone\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <input type=\"submit\" value=\"Сохранить\">
                    </div>
                    <div class=\"col-75\"></div>
                </div>
                <div class=\"clear\"></div>
            </form>
            <hr>
            <form method=\"post\" action=\"$this->url\" enctype=\"multipart/form-data\">
                <input type=\"hidden\" name=\"action\" value=\"update_profile\">
                <input type=\"hidden\" name=\"update\" value=\"avatar\">
                <div class=\"row\">
                    <div class=\"col-25\">
                        <label for=\"user_avatar\">Аватарка:</label>
                    </div>
                    <div class=\"col-75\">
                        <input type=\"file\" name=\"user_avatar\">
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-25\">
                        <input type=\"submit\" value=\"Сохранить\">
                    </div>
                    <div class=\"col-75\"></div>
                </div>
                <div class=\"clear\"></div>
                <div class=\"notes\">
                    После загрузки аватарка уменьшится до 100 пикселей в ширину.
                </div>
            </form>
        </div>
        ";
    }
    function update_login(){
        $new_login = htmlspecialchars($_REQUEST['user_login']);
        if(!$new_login){
            $this->error .= "USER: Вы не заполнили поле логин.<br>";
            return false;
        }
        if(strlen($new_login) < 5){
            $this->error .= "USER: Слишком короткий логин.<br>";
            $this->error .= "Минимум 5 символов.<br>";
            return false;
        }
        if(strcmp($new_login, $this->user_login) == 0){
            $this->error .= "USER: Данный логин совпадает со старым.<br>";
            return false;
        }
        $this->db->select(' user_login ', $this->table, "user_login = '".$new_login."'");
        $row = $this->db->fetch(MYSQLI_ASSOC);
        if(isset($row['user_login'])){
            $this->error .= "USER: Данный логин уже занят, выберите другой.<br>";
            return false;
        }
        $this->db->free();
        //updating login
        if(!$this->db->update($this->table, "user_login = '$new_login'", "user_id = $this->user_id")){
            $this->error = $this->db->error;
            return false;
        }
        $this->user_login = $new_login;
        return true;
    }
    function update_password(){
        $new_password_1 = htmlspecialchars($_REQUEST['user_password_1']);
        $new_password_2 = htmlspecialchars($_REQUEST['user_password_2']);
        if((!$new_password_1) || (!$new_password_2)){
            $this->error = "USER: Пожалуйста заполните оба поля с паролями.<br>";
            return false;
        }
        if(strcmp($new_password_1, $new_password_2) != 0){
            $this->error .= "USER: Введенные пароли не совпадают.<br>";
            return false;
        }
        $new_password = md5($new_password_1);
        if(!$this->db->update($this->table, "user_password = '$new_password'", "user_id = $this->user_id")){
            $this->error = $this->db->error;
            return false;
        }
        return true;
    }
    function update_data(){
        $user_name = htmlspecialchars($_REQUEST['user_name']);
        $user_surname = htmlspecialchars($_REQUEST['user_surname']);
        $user_last_name = htmlspecialchars($_REQUEST['user_last_name']);
        $user_email = htmlspecialchars($_REQUEST['user_email']);
        $user_phone = htmlspecialchars($_REQUEST['user_phone']);
        if(!$this->db->update($this->table, "user_name = '$user_name',
                user_surname = '$user_surname',
                user_last_name = '$user_last_name',
                user_email = '$user_email',
                user_phone = '$user_phone'", "user_id = $this->user_id")){
            $this->error = $this->db->error;
            return false;
        }
        $this->user_name = $user_name;
        $this->user_surname = $user_surname;
        $this->user_last_name = $user_last_name;
        $this->user_email = $user_email;
        $this->user_phone = $user_phone;
        return true;
    }
    function update_avatar(){
        $user_avatar = '';
        $file_name = $_FILES['user_avatar']['name'];
        $file_path = $_FILES['user_avatar']['tmp_name'];

        if($file_name){
            $user_avatar = file_get_contents($file_path);
            $user_avatar = base64_encode($this->resize_image($user_avatar));
        }
        if(!$this->db->update($this->table, "user_avatar = '$user_avatar'", "user_id = $this->user_id")){
            $this->error = $this->db->error;
            return false;
        }
        return true;
    }
    function resize_image($str){
        $image = imagecreatefromstring($str);
        if($image){
            $image = imagescale($image, 100);
            imagepng($image, '01.png');
            imagedestroy($image);
            $str = file_get_contents('01.png');
            unlink('01.png');
            return $str;
        }
        return false;
    }
}

class c_panel{
    var $db;
    var $url;
    var $user;
    var $error = '';
    var $output = '';
    var $menu = array();
    function __construct($db, $url, $user){
        $this->db = $db;        
        $this->url = $url."?action=c_panel";        
        $this->user = $user;
        if($this->user->user_priv){
            $this->menu['Панель управления'] = "$this->url&cp_action=show_pannel";        
        }
    }
    function work(){
        // actions
        if(!$this->user->user_priv){
            return false;
        }
        $cp_action = $_REQUEST['cp_action'];
        if(strcmp($cp_action, 'show_pannel') == 0){
            $this->show_panel();
        } elseif (strcmp($cp_action, 'show_user') == 0){
            $this->show_user();
        } elseif (strcmp($cp_action, 'make_admin') == 0){
            $this->db->update($this->user->table, 'user_priv = 1', 'user_id = '.$_REQUEST['user_id']);
            $this->show_user();
        } elseif (strcmp($cp_action, 'make_ban') == 0){
            $this->db->update($this->user->table, 'user_ban = 1, ban_exp = date_add(now(), interval 15 minute)', 'user_id = '.$_REQUEST['user_id']);
            $this->show_user();
        } elseif (strcmp($cp_action, 'unban_user') == 0){
            $this->db->update($this->user->table, 'user_ban = 0, ban_exp = 0', 'user_id = '.$_REQUEST['user_id']);
            $this->show_user();
        } elseif (strcmp($cp_action, 'make_user') == 0){
            $this->db->update($this->user->table, 'user_priv = 0', 'user_id = '.$_REQUEST['user_id']);
            $this->show_user();
        }
        else {
            $this->show_panel();
        }
    }
    function show_panel(){
        // output
        $show = 'all'; //banned, admin
        $user_cat = array();
        $user_cat['all'] = 'пользователей';
        $user_cat['admin'] = 'администраторов';
        $user_cat['banned'] = 'заблокированных';
        $select_cat = array();
        $select_cat['all'] = '1';
        $select_cat['admin'] = 'user_priv = 1';
        $select_cat['banned'] = 'user_ban = 1';
        if(isset($_REQUEST['users'])){
            $show = $_REQUEST['users'];
        }
        $this->output = "<div class=\"control-panel\">";
        $this->output .= "<h3>Панель управления пользователями</h3>";
        $this->output .= "<div class=\"cp-menu\">";
        $this->output .= "  <div class=\"cp-menu-item\">";
        $this->output .= "      <a href=\"$this->url&cp_action=show_pannel&users=all\">Все пользователи</a> ";
        $this->output .= "  </div>";
        $this->output .= "  <div class=\"cp-menu-item\">";
        $this->output .= "      <a href=\"$this->url&cp_action=show_pannel&users=admin\">Администраторы</a> ";
        $this->output .= "  </div>";
        $this->output .= "  <div class=\"cp-menu-item\">";
        $this->output .= "      <a href=\"$this->url&cp_action=show_pannel&users=banned\">Заблокированные</a> ";
        $this->output .= "  </div>";
        $this->output .= "</div>";
        //loading users
        $this->db->select(' * ', $this->user->table, $select_cat[$show]);
        $this->output .= "<p>Всего ".$user_cat[$show].": ".$this->db->num_rows()."</p>";
        $this->output .= "<div class=\"col-25\">Логин</div>";
        $this->output .= "<div class=\"col-75\">ФИО</div>";
        while($row = $this->db->fetch()){
            $css = '';
            if($row['user_ban']){$css = 'banned';}
            if($row['user_priv']){$css = 'admin';}
            $this->output .= "<div class=\"cp-user-row col-25 $css\"><a href=\"$this->url&cp_action=show_user&user_id=".$row['user_id']."\">".$row['user_login']."</a></div>";
            $this->output .= "<div class=\"cp-user-row col-75 $css\">".$row['user_surname']." ".$row['user_name']." ".$row['user_last_name']."</div>";
        }
        $this->db->free();
        $this->output .= "<div class=\"clear\"></div>";
        $this->output .= "</div>";
        // бан на 15 мин
        //update test_user set user_ban = 1, ban_exp = date_add(now(), interval 15 minute) where user_id = 11
    }
    function show_user(){
        // output user details
        $uid = $_REQUEST['user_id'];
        $this->output = "<div class=\"control-panel\">";
        $this->output .= "<h3>Панель управления пользователями</h3>";
        $this->output .= "<div class=\"cp-menu\">";
        $this->output .= "  <div class=\"cp-menu-item\">";
        $this->output .= "      <a href=\"$this->url&cp_action=show_pannel&users=all\">Назад к списку пользователей</a> ";
        $this->output .= "  </div>";
        $this->output .= "</div>";
        $this->db->select(' * ', $this->user->table, 'user_id='.$uid);
        if($row = $this->db->fetch()){
            $this->output .= "<div class=\"cp-user-detail\">";
            $this->output .= "  <div class=\"cp-user-avatar\">";
            $this->output .= "      <img src=\"image.php?uid=".$row['user_id']."\">";
            $this->output .= "  </div>";
            $this->output .= "  <div class=\"cp-user-login\">Логин: ".$row['user_login']."</div>";
            // You!
            if($row['user_id'] == $this->user->user_id){
                $this->output .= "  <div class=\"cp-user-menu admin\"> Вы состоите в группе Администраторы.</div>";
            } else {
                if($row['user_ban']){ // banned
                    $date = date_create($row['ban_exp']);
                    $this->output .= "  <div class=\"cp-user-menu banned\"><p>Действия данного пользователя ограничены до ".date_format($date, 'H : i')."</p>";
                    $this->output .= "      <p><a href=\"$this->url&cp_action=unban_user&user_id=".$row['user_id']."\">Разблокировать?</a></p>";
                    $this->output .= "  </div>";
                } elseif ($row['user_priv']){ // admin
                    $this->output .= "  <div class=\"cp-user-menu admin\">Данный пользователь состоит в группе Администраторы";
                    $this->output .= "  <p><a href=\"$this->url&cp_action=make_user&user_id=".$row['user_id']."\">Исключить из группы?</a></p>";
                    $this->output .= "  </div>";
                } else { // user
                    $this->output .= "  <div class=\"cp-user-menu\">";
                    $this->output .= "      <div class=\"cp-make-admin\">";
                    $this->output .= "          <a href=\"$this->url&cp_action=make_admin&user_id=".$row['user_id']."\">Сделать администратором</a>";
                    $this->output .= "      </div>";
                    $this->output .= "      <div class=\"cp-make-ban\">";
                    $this->output .= "          <a href=\"$this->url&cp_action=make_ban&user_id=".$row['user_id']."\">Блокировать на 15 мин.</a>";
                    $this->output .= "      </div>";
                    $this->output .= "  </div>";
                }
            }
            $this->output .= "  <div class=\"col-25\">Фамилия:</div>";
            $this->output .= "  <div class=\"col-75\">".$row['user_surname']."</div>";
            $this->output .= "  <div class=\"col-25\">Имя:</div>";
            $this->output .= "  <div class=\"col-75\">".$row['user_name']."</div>";
            $this->output .= "  <div class=\"col-25\">Отчество:</div>";
            $this->output .= "  <div class=\"col-75\">".$row['user_last_name']."</div>";
            $this->output .= "  <div class=\"col-25\">E-mail:</div>";
            $this->output .= "  <div class=\"col-75\">".$row['user_email']."</div>";
            $this->output .= "  <div class=\"col-25\">Телефон:</div>";
            $this->output .= "  <div class=\"col-75\">".$row['user_phone']."</div>";
            $this->output .= "  <div class=\"clear\"></div>";
            $this->output .= "</div>";
        }
        $this->db->free();
        $this->output .= "</div>";
    }
}

?>