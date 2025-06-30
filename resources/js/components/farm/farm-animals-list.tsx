import React, { useState } from 'react';
import FarmAnimalsListInputRow from '@/components/farm/farm-animals-list-input-row';
import axios from 'axios';
import AnimalTransferModal from '@/components/farm/animal-transfer-modal';

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

interface FarmAnimalListProps {
    farm: Farm
}

const getCsrfToken = () => {
    const meta = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement;
    return meta?.content;
};

const removeAnimal = async (farmId: number, animalId: number) => {

    try {
        await axios.delete(`/farms/${farmId}/animals/${animalId}/delete`, {
            headers: {
                'X-CSRF-TOKEN': getCsrfToken() || '',
                'Content-Type': 'application/json',
            },
        });

        window.location.replace(`/farms/${farmId}`)
    } catch (error) {
        console.error(error);
    }
};

const FarmAnimalList: React.FC<FarmAnimalListProps> = ({ farm }) => {
    const [showModal, setShowModal] = useState(false);
    const [animalToTransfer, setAnimalToTransfer] = useState<number | null>(null);

    const openTransfer = (animalId: number) => {
        setAnimalToTransfer(animalId);
        setShowModal(true);
    };

    return (
        <div className="w-full rounded-xl border border-gray-700 bg-gray-900 p-6 text-white shadow-sm space-y-4">
            <h2 className="text-xl font-semibold">Animals</h2>

            {farm.animals.length === 0 ? (
                <p className="text-gray-400">No animals found for this farm.</p>
            ) : (
                <div className="space-y-3">
                    {farm.animals.map((animal) => (
                        <div
                            key={animal.id}
                            className="flex justify-between items-center p-4 bg-gray-800 rounded-lg hover:bg-gray-700 transition cursor-pointer"
                        >
                            <div>
                                <p className="font-semibold text-lg">#{animal.animal_number}</p>
                                <p className="text-sm text-gray-400">{animal.type_name} — {animal.years} year(s) old</p>
                            </div>
                            <div className="flex gap-5">
                                <button onClick={() => removeAnimal(farm.id, animal.id)}>Remove</button>
                                <button onClick={() => openTransfer(animal.id)}>Transfer</button>
                            </div>
                        </div>
                    ))}
                </div>
            )}

            {farm.animals.length < 3 && <FarmAnimalsListInputRow farm={farm} />}

            <AnimalTransferModal
                open={showModal}
                onClose={() => setShowModal(false)}
                farm={farm}
                animalId={animalToTransfer!}
            />
        </div>
    );
};

export default FarmAnimalList;
