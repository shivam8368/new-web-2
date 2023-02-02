/**
 * (c) 2021 by Denis Ulmann
 */

namespace app.services
{
    export class HttpClient
    {
        private timeout: number = 5000;
        private retryCount: number = 3;


        public setTimeout(timeout: number)
        {
            this.timeout = timeout;
        }

        public setRetry(retryCount: number)
        {
            this.retryCount = retryCount;
        }

        public async getAsync(url: string, globalLoader: boolean = true): Promise<HttpResponse>
        {
            if(globalLoader)
            {
                app.lib.GlobalLoader.open();
            }

            let ajaxParams = {
                url: url,
                type: "get",
                timeout: this.timeout,
                retryLimit: this.retryCount,
            };

            let response = await this.callAsync(ajaxParams);

            if(globalLoader)
            {
                app.lib.GlobalLoader.close();
            }

            return response;
        }

        public async postAsync(url: string, data: object, globalLoader: boolean = true): Promise<HttpResponse>
        {
            if(globalLoader)
            {
                app.lib.GlobalLoader.open();
            }

            let ajaxParams = {
                url: url,
                type: "post",
                data: data,
                timeout: this.timeout,
                retryLimit: this.retryCount,
            };

            let response = await this.callAsync(ajaxParams);

            if(globalLoader)
            {
                app.lib.GlobalLoader.close();
            }

            return response;
        }

        private async callAsync(ajaxParams): Promise<HttpResponse>
        {
            let errorCallback;
            ajaxParams.tryCount = (!ajaxParams.tryCount) ? 0 : ajaxParams.tryCount;
            ajaxParams.retryLimit = (!ajaxParams.retryLimit) ? 2 : ajaxParams.retryLimit;
            ajaxParams.suppressErrors = true;
            ajaxParams.headers = {
                'HttpClientRequest': 1
            };

            let ajaxResponse = $.ajax(ajaxParams);
            try {
                await ajaxResponse.promise();
            }catch(e){ }

            if (ajaxResponse.status < 200 || ajaxResponse.status > 299)
            {
                ajaxParams.tryCount++;
                if (ajaxParams.tryCount <= ajaxParams.retryLimit)
                {
                    // fire error handling on the last try
                    if (ajaxParams.tryCount === ajaxParams.retryLimit)
                    {
                        ajaxParams.error = errorCallback;
                        delete ajaxParams.suppressErrors;
                    }

                    console.log('HttpClient failed because of: ' + ajaxResponse.statusText + '. Trying again ...');

                    //try again
                    await this.callAsync(ajaxParams);
                }
            }

            return new app.services.HttpResponse(ajaxResponse);
        }
    }

    export class HttpResponse
    {
        public statusCode: number;
        public statusText: string;
        public isSuccessStatusCode: boolean;
        public content: HttpContent;

        constructor(ajaxResponse)
        {
            this.isSuccessStatusCode = ajaxResponse.status >= 200 && ajaxResponse.status <= 299;
            this.statusCode = ajaxResponse.status;
            this.statusText = ajaxResponse.statusText;
            this.content = new HttpContent(ajaxResponse.responseText, ajaxResponse.responseJSON);
        }
    }

    export class HttpContent
    {
        private readonly responseText;
        private responseJSON;

        constructor(responseText, responseJSON)
        {
            this.responseText = responseText;
            this.responseJSON = responseJSON;
        }

        public isJson()
        {
            if(typeof this.responseJSON === 'undefined')
            {
                try {
                    JSON.parse(this.responseText);
                    return true;
                }
                catch(e)
                {
                    return false;
                }

            }

            return typeof this.responseJSON !== 'undefined';
        }

        public readAsString()
        {
            return this.responseText;
        }

        public readAsJson()
        {
            if(typeof this.responseJSON === 'undefined')
            {
                try {
                    this.responseJSON = JSON.parse(this.responseText);
                    return this.responseJSON;
                }
                catch(e)
                {
                    console.error('Failed to parse json response ' + this.responseText);
                    return false;
                }

            }

            return this.responseJSON;
        }
    }
}