import React from 'react'

const Login = () => {
    return(
      <div className='py-5 px-5'>
        <form className="form-signin">
      <h1 className="h3 mb-3 font-weight-normal">Please sign in</h1>
      <input type="email" id="inputEmail" className="form-control m-2" placeholder="Email address" required autofocus />
      <input type="password" id="inputPassword" className="form-control m-2" placeholder="Password" required />
      <div className="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me" /> Remember me
        </label>
      </div>
      <button className="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
      </div>
     )
}

export default Login
