import HeaderLayout from "./HeaderLayout"
import FooterLayout from "./FooterLayout"

export default function MainLayout({ children }) {
    return (
        <div className="flex flex-col min-h-screen">
            <HeaderLayout />
        
            <main className="flex-1">{children}</main>

            <FooterLayout />
        </div>
    )
}
