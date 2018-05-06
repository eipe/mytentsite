<template>
    <footer class="footer">
        <div class="container">
            <div class="columns">
                <div class="column is-half">
                    <h1 class="title is-5">{{ $t('spreadTheWord')}}</h1>
                    <h2 class="subtitle is-6">- {{ $t('spreadTheWordSub')}}</h2>
                    <p class="content is-small">{{ $t('spreadTheWordBody')}}</p>
                    <br>
                    <p>
                        <a href="https://instagram.com/mytentsite" target="_blank" title="Instagram - @mytentsite"
                           class="button is-light">
                            <span class="icon is-medium"><i class="fa fa-instagram"></i></span>
                            <span>Instagram</span>
                        </a>
                        <a href="https://facebook.com/mytentsite" target="_blank" title="Facebook - mytentsite"
                           class="button is-light">
                            <span class="icon is-medium"><i class="fa fa-facebook"></i></span>
                            <span>Facebook</span>
                        </a>
                    </p>
                    <br>
                    <p class="content is-small"><small>{{ $t('cookies') }}</small>
                    </p>
                </div>
                <div class="column is-half">
                    <h1 class="title is-5">{{ $t('about')}}</h1>
                    <h2 class="subtitle is-6">- {{ $t('aboutSub')}}</h2>
                    <p v-html="$t('aboutBody')"></p>
                </div>
            </div>
        </div>
    </footer>
</template>
<script>
    export default {
        name: "Footer",
        i18n: {
            messages: {
                en: {
                    spreadTheWord: 'Spread the word and inspire others',
                    spreadTheWordSub: 'to share their favourite tent sites!',
                    spreadTheWordBody: 'We encourage you to bring this message further on, in hope of a growing\n' +
                        'database of tent sites throughout the world. We will promote tent sites through our\n' +
                        'instagram account on request, to spread the word and inspire to share.',
                    about: 'About',
                    aboutSub: 'people behind and site info',
                    aboutBody: '<p class="content is-small">\n' +
                    'The concept and solution <strong>mytentsite</strong> is developed and maintained by Eipe.<br>\n' +
                    'We launched the first version in 2017 after months of planning and development and proudly\n' +
                    'present to you this collaborative tent collective!\n' +
                    '</p>\n' +
                    '<p class="content is-small">\n' +
                    'Feel free to contact us through or social profiles or directly on email\n' +
                    'mytentsite@gmail.com if you have any questions, ideas or bug reports.\n' +
                    'We really would appreciate your inputs!\n' +
                    '</p>',
                    cookies: 'We use cookies to give you the best possible user experience. ' +
                    'With continued use of our service, we assume that you agree to our use of cookies.',
                },
                no: {
                    spreadTheWord: 'Spre ordet, og inspirer andre',
                    spreadTheWordSub: 'til å dele deres favoritt teltplasser!',
                    spreadTheWordBody: 'Vi oppfordrer deg til å spre ordet, i håp om en stadig voksende samling av teltplasser rundt om i verden.',
                    about: 'Om',
                    aboutSub: 'de bak prosjektet og informasjon',
                    aboutBody: '<p class="content is-small">\n' +
                    'Konseptet og løsningen <strong>mytentsite</strong> er utviklet og vedlikeholdes av Eipe.<br>\n' +
                    'Vi lanserte den første versjonen i 2017 etter måneder med planlegging og utvikling.\n' +
                    'Dette gjør at vi stolt kan presentere vårt felles teltkollektiv!\n' +
                    '</p>\n' +
                    '<p class="content is-small">\n' +
                    'Kontakt oss gjerne via våre mytentsite kontoer, eller direkte på epost mytentsite@gmail.com om du har spørsmål, ideer eller feil å melde.\n' +
                    'Vi setter pris på alle tilbakemeldinger!\n' +
                    '</p>',
                    cookies: 'Vi benytter informasjonskapsler (cookies) for å kunne tilby deg den beste mulige brukeropplevelsen.' +
                    'Ved å benytte deg av vår tjeneste, godtar du også vår bruk av informasjonskapsler.',
                }
            }
        }
    }
</script>