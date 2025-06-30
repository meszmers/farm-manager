import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import CreateFarmForm from '@/components/farm/create-farm-form';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create new farm',
        href: '/create-farm',
    },
];

export default function Farms() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Farms" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                    <CreateFarmForm />
                </div>
            </div>
        </AppLayout>
    );
}
