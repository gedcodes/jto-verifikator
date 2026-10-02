import ApiClient from '../utils/APIClient';
const getAll = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/device/gwrouting', params).then((result) => result?.data).catch((error) => error?.response),
);

const getAllActive = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/device/gwrouting/active/publish', params).then((result) => result?.data).catch((error) => error?.response),
);

const getById = (id) => ApiClient.withAuth().then(
    (api) => api.get(`/device/gwrouting/${id}`).then((result) => result?.data).catch((error) => error?.response),
);

const create = (payload) => ApiClient.withAuth().then(
    (api) => api.post('/device/gwrouting', payload).then((result) => result?.data).catch((error) => error?.response),
);

const edit = (id, payload) => ApiClient.withAuth().then(
    (api) => api.patch(`/device/gwrouting/${id}`, payload).then((result) => result?.data).catch((error) => error?.response.data),
);

const trash = (id) => ApiClient.withAuth().then(
    (api) => api.delete(`/device/gwrouting/${id}`).then((result) => result?.data).catch((error) => error?.response),
);

const trashArr = (id = []) => ApiClient.withAuth().then(
    (api) => api.delete(`/device/gwrouting/trash/arr/${id.join()}`).then((result) => result?.data).catch((error) => error?.response),
);

const editStatusArray = (arrayIds = [], payload) => ApiClient.withAuth().then(
    (api) => api.patch(`/device/gwrouting/active/update/${arrayIds.join()}`, payload).then((result) => result?.data).catch((error) => error?.response),
);

const force = (arrayIds = []) => ApiClient.withAuth().then(
    (api) => api.delete(`/device/gwrouting/delete/force/${arrayIds.join()}`).then((result) => result?.data).catch((error) => error?.response),
);

export default {
    getAll,
    getAllActive,
    getById,
    create,
    edit,
    trash,
    trashArr,
    editStatusArray,
    force,
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
