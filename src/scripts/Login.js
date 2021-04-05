import '../styles/Login.css';

const Login = () => {
    return (
        <section id="login-form">
            <header>
                <h1>LOGIN</h1>
            </header>

            <ul className="input-box">
                <li>
                    <div className="input">
                        <label htmlFor="email-input">Email</label>
                        <input id="email-input" type="email" spellCheck="false"/>
                    </div>
                    <p className="error-message"></p>
                </li>
                <li>
                    <div className="input">
                        <label htmlFor="password-input">Password</label>
                        <input id="password-input" type="password" spellCheck="false"/>
                        <button className="toggle-show-password" aria-label="Show Password">
                            <i className="icon_eye-36b889"></i>
                        </button>
                    </div>
                    <p className="error-message"></p>
                </li>
            </ul>

            <div className="button-box">
                <button type="submit" className="button-login">Login</button>
            </div>
        </section>
    );
};

export default Login;