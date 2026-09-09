import { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function Layout({ children, title = '', subtitle = '' }) {
    const { auth } = usePage().props;
    const [dark, setDark] = useState(() => {
        if (typeof window !== 'undefined') {
            const stored = localStorage.getItem('theme');
            if (stored) return stored === 'dark';
            return window.matchMedia('(prefers-color-scheme: dark)').matches;
        }
        return false;
    });

    useEffect(() => {
        document.documentElement.classList.toggle('dark', dark);
        localStorage.setItem('theme', dark ? 'dark' : 'light');
    }, [dark]);

    return (
        <div className="ui-root">
            <div className="ui-flex ui-items-center ui-justify-between" style={{ marginBottom: '1.25rem' }}>
                <div>
                    {title && <h1 className="ui-page-title">{title}</h1>}
                    {subtitle && <p className="ui-page-subtitle">{subtitle}</p>}
                </div>
                <div className="ui-flex ui-gap-sm ui-items-center">
                    <button
                        onClick={() => setDark(!dark)}
                        className="ui-btn ui-btn-ghost ui-btn-sm"
                        style={{ padding: '0.5rem' }}
                        title="Toggle dark mode"
                    >
                        {dark ? '☀️' : '🌙'}
                    </button>
                    <Link href="/" className="ui-btn ui-btn-ghost ui-btn-sm">
                        Dashboard
                    </Link>
                </div>
            </div>
            {children}
        </div>
    );
}
