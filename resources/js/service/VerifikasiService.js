import ApiClient from '../utils/APIClient';
const getAll = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/setup/verifikasi', params).then((result) => result?.data).catch((error) => error?.response),
);

const getAllActive = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/setup/verifikasi/active/publish', params).then((result) => result?.data).catch((error) => error?.response),
);

const getById = (id) => ApiClient.withAuth().then(
    (api) => api.get(`/setup/verifikasi/${id}`).then((result) => result?.data).catch((error) => error?.response),
);

const create = (payload) => ApiClient.withAuth().then(
    (api) => api.post('/setup/verifikasi', payload).then((result) => result?.data).catch((error) => error?.response),
);

const edit = (id, payload) => ApiClient.withAuth().then(
    (api) => api.patch(`/setup/verifikasi/${id}`, payload).then((result) => result?.data).catch((error) => error?.response),
);

const updateActive = (id, payload) => ApiClient.withAuth().then(
    (api) => api.patch(`/setup/verifikasi/active/update/${id}`, payload).then((result) => result?.data).catch((error) => error?.response),
);

const remove = (id) => ApiClient.withAuth().then(
    (api) => api.delete(`/setup/verifikasi/${id}`).then((result) => result?.data).catch((error) => error?.response),
);

const editStatusArray = (arrayIds = [], payload) => ApiClient.withAuth().then(
    (api) => api.put(`/setup/verifikasi/updateArr/status?arrId=${arrayIds}`, payload).then((result) => result?.data).catch((error) => error?.response),
);

const deleteArray = (arrayIds = []) => ApiClient.withAuth().then(
    (api) => api.delete(`/setup/verifikasi/deleteArrSoft/arr?arrId=${arrayIds}`).then((result) => result?.data).catch((error) => error?.response),
);

export default {
    getAll,
    getAllActive,
    getById,
    create,
    edit,
    updateActive,
    delete: remove,
    editStatusArray,
    deleteArray,
};
// const ENDPOINT = '/api/setup/bptd';
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
