import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import FarmList from '@/components/farm/farm-list';

interface Farm {
    id: number;
    name: string;
}

interface PaginatedFarms {
    data: Farm[];
    current_page: number;
    last_page: number;
    next_page_url: string | null;
    prev_page_url: string | null;
}

interface FarmsProps {
    paginatedFarms: PaginatedFarms;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Farms',
        href: '/farms',
    },
];

export default function Farms({ paginatedFarms }: FarmsProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Farms" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative flex flex-col gap-8 overflow-hidden rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <FarmList paginatedFarms={paginatedFarms} />
                </div>
            </div>
        </AppLayout>
    );
}
