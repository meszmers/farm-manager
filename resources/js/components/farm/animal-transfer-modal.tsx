import axios from 'axios';
import React, { useEffect, useState } from 'react';

interface Farm {
    id: number
    name: string;
    email?: string | null;
    website?: string | null;
}

interface AnimalTransferModalProps {
    open: boolean;
    onClose: () => void;
    farm: Farm,
    animalId: number;
}

const AnimalTransferModal: React.FC<AnimalTransferModalProps> = ({ open, onClose, farm, animalId }) => {
    const [farms, setFarms] = useState<Farm[]>([]);
    const [selectedFarmId, setSelectedFarmId] = useState<number | null>(null);
    const [submitting, setSubmitting] = useState(false);
    const [loadingFarms, setLoadingFarms] = useState(false);

    useEffect(() => {
        if (open) {
            fetchFarms();
        }
    }, [open]);

    const fetchFarms = async () => {
        setLoadingFarms(true);
        const response = await axios.get('/farms-with-space');
        setFarms(response.data.farms.filter((farmWithSpace: Farm) => farmWithSpace.id !== farm.id));
        setLoadingFarms(false);
    };

    const handleTransfer = async () => {
        if (!selectedFarmId) return;

        setSubmitting(true);

        const formData = new FormData();
        formData.append('transfer_farm_id', String(selectedFarmId));

        await axios.post(`/farms/${farm.id}/animals/${animalId}/transfer`, formData);

        setSubmitting(false);
        onClose();
        window.location.reload();
    };

    if (!open) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60">
            <div className="w-full max-w-md rounded-lg bg-white p-6 shadow-lg dark:bg-gray-900">
                <h2 className="mb-4 text-xl font-semibold text-gray-100">Transfer Animal</h2>

                {loadingFarms ? (
                    <p className="text-gray-300">Loading farms...</p>
                ) : (
                    <div className="mb-4">
                        <label className="mb-2 block text-sm font-medium text-gray-200">Select New Farm</label>
                        <select
                            value={selectedFarmId ?? ''}
                            onChange={(e) => setSelectedFarmId(Number(e.target.value))}
                            className="w-full rounded border border-gray-600 bg-gray-700 p-2 text-white"
                        >
                            <option value="">-- Select Farm --</option>
                            {farms.map((farmWithSpace) => (
                                <option key={farmWithSpace.id} value={farmWithSpace.id}>
                                    {farmWithSpace.name}
                                </option>
                            ))}
                        </select>
                    </div>
                )}

                <div className="flex justify-end gap-2">
                    <button onClick={onClose} className="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-500">
                        Cancel
                    </button>

                    <button
                        onClick={handleTransfer}
                        disabled={!selectedFarmId || submitting}
                        className="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-500"
                    >
                        {submitting ? 'Transferring...' : 'Transfer'}
                    </button>
                </div>
            </div>
        </div>
    );
};

export default AnimalTransferModal;
