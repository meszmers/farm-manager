import React, { useState } from 'react';
import axios from 'axios';

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

interface FarmAnimalsListInputRowProps {
    farm: Farm
}

const FarmAnimalsListInputRow: React.FC<FarmAnimalsListInputRowProps> = ({ farm }) => {
    const [formData, setFormData] = useState({
        animal_number: '',
        animal_type: '',
        years: '',
    });

    const [loading, setLoading] = useState(false);

    const getCsrfToken = () => {
        const meta = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement;
        return meta?.content;
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setFormData((prev) => ({ ...prev, [name]: value }));
    };

    const handleAddAnimal = async () => {
        setLoading(true);

        if (!formData.animal_number || !formData.animal_type || !formData.years) {
            alert('Invalid input values for submitting.');
        }

        try {
            await axios.post(`/farms/${farm.id}/animals`, formData, {
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken() || '',
                    'Content-Type': 'multipart/form-data',
                },
            });

            window.location.reload();
        } catch (e) {
            console.log(e);

            setLoading(false)
        }
    };

    return (
        <div className="flex flex-col gap-2 p-4 mt-2 rounded-lg bg-gray-800 text-white border border-gray-700">
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <input
                    type="text"
                    name="animal_number"
                    placeholder="Animal Number"
                    className="w-full px-3 py-2 rounded bg-gray-700 text-white placeholder-gray-400"
                    value={formData.animal_number}
                    onChange={handleChange}
                />

                <input
                    type="text"
                    name="animal_type"
                    placeholder="Animal Type"
                    className="w-full px-3 py-2 rounded bg-gray-700 text-white placeholder-gray-400"
                    value={formData.animal_type}
                    onChange={handleChange}                />

                <input
                    type="number"
                    name="years"
                    placeholder="Years"
                    className="w-full px-3 py-2 rounded bg-gray-700 text-white placeholder-gray-400"
                    value={formData.years}
                    onChange={handleChange}                />
            </div>

            <div className="flex justify-end mt-2">
                <button
                    onClick={handleAddAnimal}
                    disabled={loading}
                    className="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded text-white font-semibold transition"
                >
                    {loading ? 'Adding...' : 'Add Animal'}
                </button>
            </div>
        </div>
    );
};

export default FarmAnimalsListInputRow;
