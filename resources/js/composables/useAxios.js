import axios from 'axios';

const instance = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
});

export function useAxios() {
    return instance;
}

export default instance;
