// hooks/usePathname.ts
import { useState, useEffect } from 'react';

export function usePathname() {
    const [pathname, setPathname] = useState('');
    
    useEffect(() => {
        setPathname(window.location.pathname);
        
        // Optional: Listen untuk perubahan URL
        const handleLocationChange = () => {
            setPathname(window.location.pathname);
        };
        
        window.addEventListener('popstate', handleLocationChange);
        
        return () => {
            window.removeEventListener('popstate', handleLocationChange);
        };
    }, [pathname]);
    
    return pathname;
}