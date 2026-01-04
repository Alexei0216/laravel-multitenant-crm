const API_URL = import.meta.env.VITE_API_URL;

export async function login(email, password) {
    const res = await fetch(`${API_URL}/api/auth/login`, {
        method: "POST",
        credentials: "include",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
    });

    const data = await res.json().catch(() => ({}));

    if (!res.ok) {
        throw new Error(data.message || "Login failed");
    }

    return data.user;
}

export const getCurrentUser = async () => {
    try {
        const res = await fetch(`${API_URL}/api/auth/me`, {
            credentials: "include",
        });

        if (!res.ok) return null;

        const data = await res.json();
        return data.user;
    } catch {
        return null;
    }
};

export const logout = async (setUser) => {
    const res = await fetch(`${API_URL}/api/auth/logout`, {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/json",
        },
    });

    if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        throw new Error(err.message || "Logout failed");
    }

    if (setUser) setUser(null);
};
