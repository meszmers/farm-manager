import { Link } from '@inertiajs/react';
import React from 'react';

interface PaginationProps {
    currentPage: number;
    lastPage: number;
    nextPageUrl: string | null;
    prevPageUrl: string | null;
}

const Pagination: React.FC<PaginationProps> = ({ currentPage, lastPage, nextPageUrl, prevPageUrl }) => {
    if (lastPage <= 1) return null;

    return (
        <div className="mt-8 flex items-center justify-between">
            <Link
                href={prevPageUrl || '#'}
                className={`rounded bg-gray-700 px-4 py-2 text-white hover:bg-gray-600 ${!prevPageUrl ? 'pointer-events-none opacity-50' : ''}`}
            >
                Previous
            </Link>

            <span className="text-sm text-gray-300">
                Page {currentPage} of {lastPage}
            </span>

            <Link
                href={nextPageUrl || '#'}
                className={`rounded bg-gray-700 px-4 py-2 text-white hover:bg-gray-600 ${!nextPageUrl ? 'pointer-events-none opacity-50' : ''}`}
            >
                Next
            </Link>
        </div>
    );
};

export default Pagination;
