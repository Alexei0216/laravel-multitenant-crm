import React, { useState, useEffect } from "react";
import { Link } from "@inertiajs/react";
import { login, logout, getCurrentUser } from "../../authService";

export default function SignIn() {
  const [data, setData] = useState({ email: "", password: "" });
  const [errors, setErrors] = useState({});
  const [user, setUser] = useState(null);

  useEffect(() => {
    getCurrentUser().then(u => setUser(u));
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});

    try {
      const loggedInUser = await login(data.email, data.password);
      setUser(loggedInUser);
    } catch (err) {
      setErrors({ common: err.message });
    }
  };

  const handleLogout = async () => {
    try {
      await logout(setUser);
    } catch (err) {
      console.error("Logout failed:", err);
    }
  };

  if (user) {
    return (
      <div className="flex flex-col items-center justify-center min-h-screen">
        <h1 className="text-2xl text-blue-600 font-bold pb-5">
          Welcome, {user.name}!
        </h1>
        <button
          onClick={handleLogout}
          className="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition duration-300"
        >
          Logout
        </button>
      </div>
    );
  }

  return (
    <div className="flex flex-col items-center justify-center min-h-screen">
      <h1 className="text-2xl text-blue-600 font-bold pb-15">SignIn Page</h1>

      <p className="pb-10">
        By signing in, you agree to our
        <Link className="text-blue-600"> Terms of Service and Privacy Policy</Link>
      </p>

      <form className="flex flex-col w-md gap-5" onSubmit={handleSubmit}>
        {errors.common && <p className="text-red-600">{errors.common}</p>}

        <div className="flex flex-col gap-2">
          <label>Email: </label>
          <input
            type="text"
            value={data.email}
            onChange={e => setData({ ...data, email: e.target.value })}
            placeholder="Enter your email"
            className={`border rounded-md py-2 px-4 ${errors.email ? "border-red-500" : "border-gray-500"}`}
          />
        </div>

        <div className="flex flex-col gap-2">
          <label>Password: </label>
          <input
            type="password"
            value={data.password}
            onChange={e => setData({ ...data, password: e.target.value })}
            placeholder="Enter your password"
            className={`border rounded-md py-2 px-4 ${errors.password ? "border-red-500" : "border-gray-500"}`}
          />
        </div>

        <button type="submit" className="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">
          Sign In
        </button>
      </form>

      <p className="pt-5">
        Not have an account? <Link className="text-blue-600">SignUp</Link>
      </p>
    </div>
  );
}
