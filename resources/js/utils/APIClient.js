import axios from 'axios';


const Client = axios.create({
    // baseURL: Config.API_URL,
    baseURL: '/api', // Config.API_URL source from appconfig.json,
    headers: {
        'Content-Type': 'application/json',
    },
    // timeout: 5000,
});

const getIntance = (url, params, config) => Client.get(url, {
    params,
    ...config,
});

const postIntance = (url, data, config) => Client.post(url, data, {
    ...config,
});

const putIntance = (url, data, config) => Client.put(url, data, {
    ...config,
});

const patchIntance = (url, data, config) => Client.patch(url, data, {
    ...config,
});

const deleteIntance = (url, config) => Client.delete(url, {
    ...config,
});

const withAuth = async () => {
    // const { token } = localStorage.getItem('token'); // await getToken();
    const setConfig = (config) => ({
        ...config,
        headers: {
            ...config?.headers,
            Authorization: localStorage.getItem('token'),
        },
    });

    return {
        get: (url, params, config) => getIntance(url, params, setConfig(config)),
        post: (url, data, config) => postIntance(url, data, setConfig(config)),
        put: (url, data, config) => putIntance(url, data, setConfig(config)),
        patch: (url, data, config) => patchIntance(url, data, setConfig(config)),
        delete: (url, config) => deleteIntance(url, setConfig(config)),
    };
};

export default {
    get: getIntance,
    post: postIntance,
    put: putIntance,
    patch: patchIntance,
    delete: deleteIntance,
    withAuth,
};
