import Utils = app.lib.Utils;

class Thread
{
    public static sleep(ms: number)
    {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
}

class DependencyInjection
{
    private instancesStorage: object = {};

    public set(name: string, instance: any)
    {
        this.instancesStorage[name] = instance;
    }

    public get(name: string)
    {
        if(!this.instancesStorage.hasOwnProperty(name))
        {

            throw new Error(
                'Instance ' + name + ' is not part of Dependency Injection'
            );
        }

        return this.instancesStorage[name];
    }
}

// Fill DI
var _DI = new DependencyInjection();
_DI.set('Utils', new Utils());
