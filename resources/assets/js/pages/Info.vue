<template>
    <div class="has-fixed-header">
        <section class="hero is-small is-info">
            <div class="hero-body">
                <div class="container">
                    <transition enter-active-class="animated fadeIn">
                        <div class="columns is-mobile" v-show="isLoaded" v-cloak>
                            <div class="column has-text-centered">
                                <p class="title is-marginless is-paddingless">{{ statistics.numberOfTentSites }}</p>
                                <p class="heading">{{ $t('info.tentSites')}}</p>
                            </div>
                            <div class="column has-text-centered">
                                    <p class="title is-marginless is-paddingless">{{ statistics.numberOfBookmarkedTentSites }}</p>
                                    <p class="heading">{{ $t('info.bookmarks')}}</p>
                            </div>
                            <div class="column has-text-centered">
                                <p class="title is-marginless is-paddingless">{{ statistics.numberOfCountries }}</p>
                                <p class="heading">{{ $t('info.countries')}}</p>
                            </div>
                            <div class="column has-text-centered">
                                <div>
                                    <p class="title is-marginless is-paddingless">{{ statistics.numberOfContributors }}</p>
                                    <p class="heading">{{ $t('info.contributors')}}</p>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </section>
        <section class="hero">
            <div class="hero-body">
                <transition enter-active-class="animated fadeIn">
                    <div class="container" v-if="isLoaded" v-cloak>
                        <h1 class="title">{{ $t('vision.title')}}</h1>
                        <h2 class="subtitle">{{ $t('vision.subTitle')}}</h2>
                        <p v-html="$t('vision.body')"></p><br>
                        <h3 class="subtitle">{{ $t('care.title')}}</h3>
                        <p v-html="$t('care.body')"></p>
                    </div>
                </transition>
            </div>
        </section>
        <section class="hero is-small is-light">
            <div class="hero-body">
                <div class="container">
                    <transition enter-active-class="animated fadeIn">
                        <nav class="level" v-if="isLoaded" v-cloak>
                            <div class="level-item has-text-centered">
                                <router-link to="share">
                                <div>
                                    <p class="title">{{ $t('action.share')}}<i class="fa fa-camera icon is-large"></i></p>
                                    <p class="heading">{{ $tc('share.title')}}</p>
                                </div>
                                </router-link>
                            </div>
                            <div class="level-item has-text-centered">
                                <router-link to="locate">
                                <div>
                                    <p class="title">{{ $t('action.locate')}}<i class="fa fa-map icon is-large"></i></p>
                                    <p class="heading">{{ $tc('locate.title')}}</p>
                                </div>
                                </router-link>
                            </div>
                            <div class="level-item has-text-centered">
                                <router-link to="explore">
                                <div>
                                    <p class="title">{{ $t('action.explore')}}<i class="fa fa-th icon is-large"></i></p>
                                    <p class="heading">{{ $tc('explore.title')}}</p>
                                </div>
                                </router-link>
                            </div>
                        </nav>
                    </transition>
                </div>
            </div>
        </section>
        <transition enter-active-class="animated fadeIn">
            <section class="hero is-medium" id="info-share" v-if="isLoaded">
                <div class="hero-body">
                    <div class="container">
                        <h1 class="title">{{ $tc('share.guide.title')}}</h1>
                        <h2 class="subtitle">{{ $tc('share.guide.subTitle')}}</h2>
                        <hr/>
                        <div class="columns">
                            <div class="column">
                                <p class="subtitle is-4" v-html="$t('share.guide.stepOneTitle')"></p>
                                <p v-html="$t('share.guide.stepOneBody')"></p>
                            </div>
                            <div class="column">
                                <p class="subtitle is-4">{{ $t('share.guide.stepTwoTitle')}}</p>
                                <p v-html="$t('share.guide.stepTwoBody')"></p>
                            </div>
                            <div class="column">
                                <p class="subtitle is-4">{{ $t('share.guide.stepThreeTitle')}}</p>
                                <p v-html="$t('share.guide.stepThreeBody')"></p>
                            </div>
                            <div class="column">
                                <p class="subtitle is-4">{{ $t('share.guide.stepFourTitle')}}</p>
                                <p v-html="$t('share.guide.stepFourBody')"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </transition>
        <footer-component/>
    </div>
</template>
<script>

    import Footer from "../components/Footer.vue"

    export default {
        name: "Info",
        i18n: {
            messages: {
                en: {
                    info: {
                        tentSites: 'Shared tent sites',
                        bookmarks: 'Bookmarked tent sites',
                        countries: 'Countries represented',
                        contributors: 'Contributors',
                    },
                    share: {
                        title: 'Your favourites with others',
                        guide: {
                            title: 'How do I share?',
                            subTitle: 'Select a photo with location, add a caption and share it!',
                            stepOneTitle: '1. Select a photo <small>- with location</small>',
                            stepOneBody: 'Take a new photo or upload one from your collection. Preferably a photo that' +
                            'brings out what you think is great with just the place.' +
                            '<br><br>It is not important whether the tent site is located on top of a\n' +
                            'mountain or beside a lake in the middle of the forrest.\n' +
                            'But to make it possible to locate the tent site and place it on the map, we need\n' +
                            'the GPS information.\n',
                            stepTwoTitle: '2. Add information',
                            stepTwoBody: 'Tell other campers why this is a preferable tent site,\n' +
                            'and give some extra information you think can help them make a decision of where\n' +
                            'to go camping. Be short and concise, we can see your photo.\n',
                            stepThreeTitle: '3. Review your tent site',
                            stepThreeBody: 'Before you press share, you might want to have another look at the details.\n' +
                            'You can now see how the tent site will be presented when we have approved it.',
                            stepFourTitle: '4. Share it!',
                            stepFourBody: 'Once you have added the details you think is relevant for the photo, just hit the\n' +
                            'share button! Your contribution will then be reviewed before it is published to the wall.\n' +
                            'You can at any time access your own contributions through your personal page.\n',
                        }
                    },
                    locate: {
                        title: 'Tent sites all over the world',
                    },
                    explore: {
                        title: 'Tent sites on the photo wall',
                    },
                    vision: {
                        title: 'The vision of mytentsite and the story behind',
                        subTitle: 'What is it? And why did we create it?',
                        body: 'The main purpose of this service is to <strong>make it easy to find a tent site</strong> without making a lot\n' +
                        'of research.<br>\n' +
                        'When traveling around it can be hard to find a tent site if you are not planning to stay at established camp sites.<br>\n' +
                        'We host this service to inspire others using and taking care of our great nature.\n',
                    },
                    care: {
                        title: 'Take care of the nature and local environment',
                        body: 'The nature is free to use, but it is our responsibility to keep it intact so others can enjoy the same as us.<br><br>\n' +
                        'We also want to encourage you to follow local restrictions and make sure that you <strong>bring all trash and non biodegradable material for recycling</strong>!',
                    },
                },
                no: {
                    info: {
                        tentSites: 'Delte teltplasser',
                        bookmarks: 'Bokmerkede teltplasser',
                        countries: 'Land representert',
                        contributors: 'Bidragsytere'
                    },
                    share: {
                        title: 'Dine favoritter med andre',
                        guide: {
                            title: 'Hvordan deler jeg?',
                            subTitle: 'Velg ett foto med lokasjon, legg til informasjon og del det!',
                            stepOneTitle: '1. Velg ett foto <small>- med lokasjonsdata</small>',
                            stepOneBody: 'Ta ett nytt bilde eller velg ett fra albumet ditt. Fortrinnsvis ett bilde som fremhever hva du synes er bra med akkurat den plassen.\n' +
                            '<br><br>Det er ikke viktig om teltplassen er på toppen av ett fjell eller ved siden av en innsjø midt inne i skogen\n' +
                            'Men for å gjøre det mulig å lokalisere på kartet er vi avhengige av GPS informasjon.',
                            stepTwoTitle: '2. Legg til informasjon',
                            stepTwoBody: 'Fortell andre naturnytere hvorfor du liker denne teltplassen og gi gjerne litt ekstra informasjon som du tror kan hjelpe å gjøre avgjørelsen om hvor en skal telte litt enklere.\n' +
                            'Vær kort og konkret - de kan se bildet.\n',
                            stepThreeTitle: '3. Se over din teltplass',
                            stepThreeBody: 'Før du deler kan det være lurt å se over detaljene. Her får du se akkurat hvordan din teltplass blir presentert når den er godkjent.',
                            stepFourTitle: '4. Del!',
                            stepFourBody: 'Når du har delt din teltplass vil vi se over ditt bidrag før det blir publisert.\n' +
                            'Du kan til enhver tid kontrollere dine bidrag via profilsiden.',
                        },
                    },
                    locate: {
                        title: 'Teltplasser over hele verden',
                    },
                    explore: {
                        title: 'Teltplasser på foto-veggen',
                    },
                    vision: {
                        title: 'Visjonen bak mytentsite og historien som satte oss i gang',
                        subTitle: 'Hva er det? Og hvorfor lagde vi denne tjenesten?',
                        body: 'Hovedformålet med denne tjenesten er å <strong>gjøre det enkelt å finne en teltplass</strong>.<br>' +
                        'Når en reiser rundt eller skal ut på tur kan det være utfordrende å finne en teltplass som dekker dine behov om du ikke har planlagt å bo på campingplass.<br>\n' +
                        'Med denne tjenesten ønsker vi å inspirere andre til å dra på tur og nyte vår vakre natur.',
                    },
                    care: {
                        title: 'Ta vare på naturen og omgivelsene',
                        body: 'Naturen er fri for alle, men det er også vårt ansvar å ta vare på naturen slik at andre også kan nyte den.<br>\n' +
                        'Vi oppfordrer all til å følge lokale restriksjoner og ferdselsregler, og sørg for <strong>å ta med all ikke-nedbrytbart materiale hjem</strong>!',
                    },
                }
            }
        },
        data() {
            return {
                statistics: {
                    numberOfTentSites: 0,
                    numberOfBookmarkedTentSites: 0,
                    numberOfCountries: 0,
                    numberOfContributors: 0,
                },
                isLoaded: false
            }
        },
        created() {
            let me = this;

            Vue.axios.get('statistics').then((response) => {
                me.statistics.numberOfTentSites = response.data.tentSites;
                me.statistics.numberOfBookmarkedTentSites = response.data.bookmarkedTentSites;
                me.statistics.numberOfCountries = response.data.countries;
                me.statistics.numberOfContributors = response.data.contributors;
            });

            setTimeout(function() {
                me.isLoaded = true;
            }, 500)
        },
        components: {
            "footer-component": Footer
        }
    }
</script>
