export async function makeRequest(url) {
    try {
        const response = await fetch(url);
        return await response.json();
    } catch (error) {
        throw new Error('Произошла ошибка при отправке запроса: ' + error.message);
    }
}

export async function makeRequestPost(url) {
    const options = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'}
    };
    try {
        const response = await fetch(url, options);
        return await response.json();
    } catch (error) {
        throw new Error('Произошла ошибка при отправке запроса: ' + error.message);
    }
}

export async function makeRequestPostJson(url, json) {
    const options = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(json)
    };

    try {
        const response = await fetch(url, options);
        return await response.json();
    } catch (error) {
        throw new Error('Произошла ошибка при отправке запроса: ' + error.message);
    }
}
