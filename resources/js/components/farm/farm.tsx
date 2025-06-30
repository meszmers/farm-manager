import FarmAnimalList from '@/components/farm/farm-animals-list';
import React from 'react';

interface Animal {
    id: number;
    animal_number: number;
    type_name: string;
    years: number;
}

interface Farm {
    id: number
    name: string;
    email?: string | null;
    website?: string | null;
    animals: Array<Animal>;
}

interface FarmProps {
    farm: Farm
}

const FarmDetails: React.FC<FarmProps> = ({ farm }) => {
    return (
        <div className="w-full rounded-xl border border-gray-700 bg-gray-900 p-6 text-white shadow-sm">
            <h1 className="text-2xl font-bold">{farm.name}</h1>

            <p className="mt-2 text-sm text-gray-400">
                📧 <span className="ml-1">{farm.email || '-'}</span>
            </p>

            <p className="mt-1 text-sm text-gray-400">
                🔗{' '}
                <a href={farm.website || undefined} target="_blank" rel="noopener noreferrer" className="ml-1 underline transition hover:text-blue-400">
                    {farm.website || '-'}
                </a>
            </p>

            <div className="mt-10">
                <FarmAnimalList
                    farm={farm}
                />
            </div>
        </div>
    );
};

export default FarmDetails;
