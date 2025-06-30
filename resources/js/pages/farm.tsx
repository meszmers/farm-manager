import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import Farm from '@/components/farm/farm';

interface Animal {
    id: number;
    animal_number: number;
    type_name: string;
    years: number;
}

interface Farm {
    id: number;
    name: string;
    email: null|string,
    website: null|string,
    animals: Array<Animal>
}

interface FarmInfo {
    farm: Farm
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Farm',
        href: '/farms/',
    },
];

export default function Farms({farm}: FarmInfo) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Farms" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                    <Farm
                        farm={farm}
                    />
                </div>
            </div>
        </AppLayout>
    );
}
