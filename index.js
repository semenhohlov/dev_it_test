function startup(){
    const user_login = document.getElementById('user_login');
    const user_password_1 = document.getElementById('user_password_1');
    const user_password_2 = document.getElementById('user_password_2');
    const error_box = document.getElementById('errors_reg_form');


    user_login.onblur = () => {
        let str = user_login.value;
        if(!str || (str.length < 5)){
            user_login.classList.add('invalid');
            error_box.innerHTML = "Минимальная длина логина 5 символов.";
        }
    }
    user_login.onfocus = () => {
        if(user_login.classList.contains('invalid')){
            user_login.classList.remove('invalid');
            error_box.innerHTML = "";
        }
    }
    user_password_1.onblur = () => {
        let str = user_password_1.value;
        if(!str){
            user_password_1.classList.add('invalid');
            error_box.innerHTML = "Введите пароль.";
        }
    }
    user_password_1.onfocus = () => {
        if(user_password_1.classList.contains('invalid')){
            user_password_1.classList.remove('invalid');
            error_box.innerHTML = "";
        }
    }
    user_password_2.onblur = () => {
        let str = user_password_1.value;
        let str2 = user_password_2.value;
        if((!str) || (!str2)){
            user_password_2.classList.add('invalid');
            error_box.innerHTML = "Введите пароль в оба поляю";
        }
        if(str != str2){
            user_password_2.classList.add('invalid');
            error_box.innerHTML = "Пароли не совпадают.";
        }
    }
    user_password_2.onfocus = () => {
        if(user_password_2.classList.contains('invalid')){
            user_password_2.classList.remove('invalid');
            error_box.innerHTML = "";
        }
    }
}

window.addEventListener('load', startup, false);