import React from "react";
import Pagination from "@/components/ui/pagination";

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

interface FarmListProps {
    paginatedFarms: PaginatedFarms;
}

function redirectToFarm(id: number) {
    window.location.replace('/farms/' + id)
}

const FarmList: React.FC<FarmListProps> = ({ paginatedFarms }) => {
    return (
        <div>
            <h2 className="text-2xl font-semibold text-white mb-4">All Farms</h2>

            {paginatedFarms.data.length === 0 ? (
                <p className="text-gray-400">No farms found.</p>
            ) : (
                <div className="flex flex-col space-y-4">
                    {paginatedFarms.data.map((farm) => (
                        <div
                            key={farm.id}
                            onClick={() => redirectToFarm(farm.id)}
                            className="rounded-xl px-6 py-4 bg-gray-800 hover:bg-gray-700 transition duration-200 cursor-pointer border border-gray-700 hover:border-blue-500"
                        >
                            <span className="text-lg font-medium text-white">{farm.name}</span>
                        </div>
                    ))}
                </div>
            )}

            <Pagination
                currentPage={paginatedFarms.current_page}
                lastPage={paginatedFarms.last_page}
                nextPageUrl={paginatedFarms.next_page_url}
                prevPageUrl={paginatedFarms.prev_page_url}
            />
        </div>
    );
};

export default FarmList;
