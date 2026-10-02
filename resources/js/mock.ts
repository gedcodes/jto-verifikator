import { Item } from "vue3-easy-data-table";

export const mockClientItems = (itemsNumber = 100): Item[] => {
    const mockItems: Item[] = [];
    const sports = ["basketball", "football", "running", "swimming"];
    const fruits = ["banana", "apple", "orange", "peach"];

    for (let i = 1; i < itemsNumber + 1; i += 1) {
        mockItems.push({
            name: `name-${i}`,
            address: `address-${i}`,
            height: i,
            weight: i,
            age: i,
            favouriteSport: sports[i % 4],
            favouriteFruits: fruits[i % 4]
        });
    }
    return mockItems;
};
