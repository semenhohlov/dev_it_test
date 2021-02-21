<?php

class _application{
    var $db;
    var $url;
    var $user;
    var $action = '';
    var $error = '';
    var $title = 'Тестовое задание';
    var $main_header = 'Здесь название сайта';
    var $menu = array();
    var $output = '';
    var $static_pages;
    var $c_panel;
    var $footer = 'Some footer.';

    function __construct($_db_config){
        $this->url = $_SERVER['PHP_SELF'];
        $this->db = new c_db($_db_config);
        if(isset($_REQUEST['action'])) $this->action = $_REQUEST['action'];
        $this->user = new c_user($this->db, $_db_config['db_table']);
        $this->static_pages = new c_static($this->url);
        $this->c_panel = new c_panel($this->db, $this->url, $this->user);
        $this->menu = array_merge($this->user->menu, $this->c_panel->menu, $this->static_pages->menu);
    }

    function show_errors(){
        $this->error .= $this->db->error;
        $this->error .= $this->user->error;
        //$this->error .= 'APP: Some test error...<br>';
        if($this->error){
            echo "<div class=\"error\">$this->error</div>";
        }
    }

    function work(){
        if($this->action){
            if(strcmp($this->action, 'login') == 0){
                $this->user->login_form();
                $this->output = $this->user->output;
            } elseif (strcmp($this->action, 'auth') == 0){
                if($this->user->auth()){
                    $user_login = htmlspecialchars($_REQUEST['user_login']);
                    $user_hash = md5($user_login.rand());
                    $_SESSION['user_hash'] = $user_hash;
                    setcookie('user_login', $user_login, time()+60*60);
                    setcookie('user_hash', $user_hash, time()+60*60);
                    header("Location: $this->url");
                } else {
                    $this->user->login_form();
                    $this->output = $this->user->output;
                }
            } elseif (strcmp($this->action, 'register_form') == 0){
                $this->user->register_form();
                $this->output = $this->user->output;
            } elseif (strcmp($this->action, 'add_user') == 0){
                if($this->user->add_user()){
                    $user_login = htmlspecialchars($_REQUEST['user_login']);
                    $user_hash = md5($user_login.rand());
                    $_SESSION['user_hash'] = $user_hash;
                    setcookie('user_login', $user_login, time()+60*60);
                    setcookie('user_hash', $user_hash, time()+60*60);
                    header("Location: $this->url");
                } else {
                    $this->user->register_form();
                    $this->output = $this->user->output;
                }
            } elseif (strcmp($this->action, 'log_off') == 0){
                unset($_SESSION['user_hash']);
                unset($_COOKIE['user_hash']);
                unset($_COOKIE['user_login']);
                header("Location: $this->url");
            } elseif (strcmp($this->action, 'edit_profile') == 0){
                $this->user->edit_form();
                $this->output = $this->user->output;
            } elseif (strcmp($this->action, 'update_profile') == 0){
                $update = $_REQUEST['update'];
                if(strcmp($update, 'login') == 0){
                    if($this->user->update_login()){
                        unset($_SESSION['user_hash']);
                        unset($_COOKIE['user_hash']);
                        unset($_COOKIE['user_login']);
                        $user_login = $this->user->user_login;
                        $user_hash = md5($user_login.rand());
                        $_SESSION['user_hash'] = $user_hash;
                        setcookie('user_login', $user_login, time()+60*60);
                        setcookie('user_hash', $user_hash, time()+60*60);
                    }
                    $this->user->edit_form();
                    $this->output = $this->user->output;
                } elseif (strcmp($update, 'password') == 0){
                    if($this->user->update_password()){
                        $this->error .= "APP: Новый пароль сохранен.<br>";
                    }
                    $this->user->edit_form();
                    $this->output = $this->user->output;
                } elseif (strcmp($update, 'user_data') == 0){
                    $this->user->update_data();
                    $this->user->edit_form();
                    $this->output = $this->user->output;
                } elseif (strcmp($update, 'avatar') == 0){
                    $this->user->update_avatar();
                    $this->user->edit_form();
                    $this->output = $this->user->output;
                }
            } elseif (strcmp($this->action, 'show_static') == 0){
                $item = $_REQUEST['item'];
                $this->output = $this->static_pages->content[$item];
            } elseif (strcmp($this->action, 'c_panel') == 0){
                $this->c_panel->work();
                $this->error .= $this->c_panel->error;
                $this->output = $this->c_panel->output;
            }
        } else{ // no action
            $this->output = $this->static_pages->content['feed'];
        }
    }
}

// static pages
class c_static{
    var $menu = array();
    var $content;
    function __construct($url){
        $this->menu['Новости'] = "$url?action=show_static&item=feed";
        $this->menu['Контакты'] = "$url?action=show_static&item=contacts";
        $this->menu['О нас'] = "$url?action=show_static&item=about";
        $this->content = array();
        $this->content['feed'] = <<<EOD
        <h3>Some feed</h3>
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolore facilis voluptatum cumque saepe voluptas excepturi aperiam iusto non voluptate harum minus laudantium sequi doloribus incidunt, illum ipsa optio cupiditate? Voluptate quis cupiditate nemo, voluptatum magni eveniet dignissimos. Odio veniam recusandae facere! Repellendus ut aperiam accusamus ullam soluta magnam veritatis totam dignissimos! Quaerat ab tempora quos ex molestiae dolor officiis! Nulla fugit molestiae nostrum ea amet harum sunt modi natus! Ullam deserunt at deleniti quidem fugit, accusamus sapiente facilis maxime reiciendis voluptas? Expedita eos aperiam sequi? Adipisci id reprehenderit natus recusandae hic libero quam repellendus, omnis molestias ipsum laboriosam ducimus facilis neque sint quia! Necessitatibus saepe beatae eaque? Dolore ipsa exercitationem cupiditate debitis iste, harum deleniti hic. Magnam nam incidunt reiciendis hic. Qui nisi suscipit aliquid voluptatem nihil libero error eaque, iste ut sunt quas aperiam doloremque tempore possimus cumque molestiae, accusamus quod repudiandae minus esse ratione. Ad voluptate, blanditiis quia deserunt eligendi quis totam quam aliquid natus placeat non magni facilis in quae odit rem officiis! Consectetur incidunt tempore perferendis totam maxime sapiente voluptatibus exercitationem ea quia ad quod recusandae quos quidem inventore provident non, sunt molestiae! A, voluptate. Asperiores culpa quis dicta magni accusamus praesentium odio, aperiam laboriosam voluptatem reiciendis architecto. Similique veniam nesciunt recusandae quasi itaque dignissimos cum ea accusamus repellat temporibus minus officiis adipisci quos doloribus repellendus, quidem expedita voluptates dolore corrupti. Assumenda impedit error dolores, facilis quod alias eaque autem! Exercitationem eaque animi tempore quod aliquid similique autem quisquam sunt fugiat, quibusdam, reprehenderit id ullam neque quidem sit ducimus ex praesentium delectus nobis accusamus mollitia? Placeat numquam ab quas quisquam, consequatur, culpa repudiandae illum ipsum modi, corrupti porro suscipit a tempora pariatur dicta aspernatur. Illo fuga non qui repudiandae? Laborum, molestias exercitationem quam, explicabo corporis reiciendis voluptas unde, et officiis ab odio doloribus blanditiis doloremque eum!
        EOD;
        $this->content['contacts'] = <<<EOD
        <h3>Наши контакты</h3>
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolore facilis voluptatum cumque saepe voluptas excepturi aperiam iusto non voluptate harum minus laudantium sequi doloribus incidunt, illum ipsa optio cupiditate? Voluptate quis cupiditate nemo, voluptatum magni eveniet dignissimos. Odio veniam recusandae facere! Repellendus ut aperiam accusamus ullam soluta magnam veritatis totam dignissimos! Quaerat ab tempora quos ex molestiae dolor officiis! Nulla fugit molestiae nostrum ea amet harum sunt modi natus! Ullam deserunt at deleniti quidem fugit, accusamus sapiente facilis maxime reiciendis voluptas? Expedita eos aperiam sequi? Adipisci id reprehenderit natus recusandae hic libero quam repellendus, omnis molestias ipsum laboriosam ducimus facilis neque sint quia! Necessitatibus saepe beatae eaque? Dolore ipsa exercitationem cupiditate debitis iste, harum deleniti hic. Magnam nam incidunt reiciendis hic. Qui nisi suscipit aliquid voluptatem nihil libero error eaque, iste ut sunt quas aperiam doloremque tempore possimus cumque molestiae, accusamus quod repudiandae minus esse ratione. Ad voluptate, blanditiis quia deserunt eligendi quis totam quam aliquid natus placeat non magni facilis in quae odit rem officiis! Consectetur incidunt tempore perferendis totam maxime sapiente voluptatibus exercitationem ea quia ad quod recusandae quos quidem inventore provident non, sunt molestiae! A, voluptate. Asperiores culpa quis dicta magni accusamus praesentium odio, aperiam laboriosam voluptatem reiciendis architecto. Similique veniam nesciunt recusandae quasi itaque dignissimos cum ea accusamus repellat temporibus minus officiis adipisci quos doloribus repellendus, quidem expedita voluptates dolore corrupti. Assumenda impedit error dolores, facilis quod alias eaque autem! Exercitationem eaque animi tempore quod aliquid similique autem quisquam sunt fugiat, quibusdam, reprehenderit id ullam neque quidem sit ducimus ex praesentium delectus nobis accusamus mollitia? Placeat numquam ab quas quisquam, consequatur, culpa repudiandae illum ipsum modi, corrupti porro suscipit a tempora pariatur dicta aspernatur. Illo fuga non qui repudiandae? Laborum, molestias exercitationem quam, explicabo corporis reiciendis voluptas unde, et officiis ab odio doloribus blanditiis doloremque eum!
        EOD;
        $this->content['about'] = <<<EOD
        <h3>О нас.</h3>
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolore facilis voluptatum cumque saepe voluptas excepturi aperiam iusto non voluptate harum minus laudantium sequi doloribus incidunt, illum ipsa optio cupiditate? Voluptate quis cupiditate nemo, voluptatum magni eveniet dignissimos. Odio veniam recusandae facere! Repellendus ut aperiam accusamus ullam soluta magnam veritatis totam dignissimos! Quaerat ab tempora quos ex molestiae dolor officiis! Nulla fugit molestiae nostrum ea amet harum sunt modi natus! Ullam deserunt at deleniti quidem fugit, accusamus sapiente facilis maxime reiciendis voluptas? Expedita eos aperiam sequi? Adipisci id reprehenderit natus recusandae hic libero quam repellendus, omnis molestias ipsum laboriosam ducimus facilis neque sint quia! Necessitatibus saepe beatae eaque? Dolore ipsa exercitationem cupiditate debitis iste, harum deleniti hic. Magnam nam incidunt reiciendis hic. Qui nisi suscipit aliquid voluptatem nihil libero error eaque, iste ut sunt quas aperiam doloremque tempore possimus cumque molestiae, accusamus quod repudiandae minus esse ratione. Ad voluptate, blanditiis quia deserunt eligendi quis totam quam aliquid natus placeat non magni facilis in quae odit rem officiis! Consectetur incidunt tempore perferendis totam maxime sapiente voluptatibus exercitationem ea quia ad quod recusandae quos quidem inventore provident non, sunt molestiae! A, voluptate. Asperiores culpa quis dicta magni accusamus praesentium odio, aperiam laboriosam voluptatem reiciendis architecto. Similique veniam nesciunt recusandae quasi itaque dignissimos cum ea accusamus repellat temporibus minus officiis adipisci quos doloribus repellendus, quidem expedita voluptates dolore corrupti. Assumenda impedit error dolores, facilis quod alias eaque autem! Exercitationem eaque animi tempore quod aliquid similique autem quisquam sunt fugiat, quibusdam, reprehenderit id ullam neque quidem sit ducimus ex praesentium delectus nobis accusamus mollitia? Placeat numquam ab quas quisquam, consequatur, culpa repudiandae illum ipsum modi, corrupti porro suscipit a tempora pariatur dicta aspernatur. Illo fuga non qui repudiandae? Laborum, molestias exercitationem quam, explicabo corporis reiciendis voluptas unde, et officiis ab odio doloribus blanditiis doloremque eum!
        EOD;
    }
}
?>