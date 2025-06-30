import axios from 'axios';
import React, { useState } from 'react';

const CreateFarmForm = () => {
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        website: '',
    });

    const [isSubmitting, setIsSubmitting] = useState(false);

    const getCsrfToken = () => {
        const meta = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement;
        return meta?.content;
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setFormData((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        try {
            await axios.post('/farms', formData, {
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken() || '',
                    'Content-Type': 'application/json',
                },
            });

            window.location.replace('/farms')
        } catch (error) {
            console.error(error);
        }
    };

    return (
        <form onSubmit={handleSubmit} className="max-w-md space-y-4 rounded-xl bg-black p-6 text-white shadow-md">
            <div>
                <label className="mb-1 block text-sm">
                    Name
                    <span className="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    required
                    value={formData.name}
                    onChange={handleChange}
                    className="w-full rounded border border-gray-600 bg-gray-800 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                />
            </div>

            <div>
                <label className="mb-1 block text-sm">Email (optional)</label>
                <input
                    type="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    className="w-full rounded border border-gray-600 bg-gray-800 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                />
            </div>

            <div>
                <label className="mb-1 block text-sm">Website (optional)</label>
                <input
                    type="url"
                    name="website"
                    value={formData.website}
                    onChange={handleChange}
                    className="w-full rounded border border-gray-600 bg-gray-800 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                />
            </div>

            <button type="submit" disabled={isSubmitting} className="w-full rounded bg-gray-800 px-4 py-2 text-white transition hover:bg-gray-700">
                {isSubmitting ? 'Submitting...' : 'Create Farm'}
            </button>
        </form>
    );
};

export default CreateFarmForm;
