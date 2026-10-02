import ApiClient from '../utils/APIClient';
import ApiClientJTO from '../utils/APIClientJTO';

const getById = (id) => ApiClientJTO.withAuth().then(
    (api) => api.get(`/v2pv/role/${id}`).then((result) => result?.data).catch((error) => error?.response),
);

export default {
    getById,
};
// const ENDPOINT = '/api/verifikasi/bptd';
// export default {
//     getAll(params = '') {
//         return Axios.get(`${ENDPOINT}`);
//     },
//     get(id) {
//         return Axios.get(`${ENDPOINT}/${id}`);
//     },
//     create(data) {
//         return Axios.post(ENDPOINT, data);
//     },
//     update(id, data) {
//         return Axios.patch(`${ENDPOINT}/${id}`, data);
//     },
//     delete(id) {
//         return Axios.delete(`${ENDPOINT}/${id}`);
//     }
// };
