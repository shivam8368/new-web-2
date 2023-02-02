<footer>
    <div class="container">
        <div>
            <div>
                {{ env('APP_CONTACT') }}
            </div>

            <div>
                 © by {{ env('APP_NAME') }}, {{ now()->year }}

                <div>
                    Powered by <a href="https://adultvideosapi.com" target="_blank">Adult Videos API</a>, create your own Porn site.
                </div>
            </div>

            <div>
                <a href="https://www.rtalabel.org/" target="_blank">
                    <div class="rta-label">
                        <img src="/assets/front/img/rta-label.gif">
                    </div>
                </a>
            </div>
        </div>
    </div>
</footer>
