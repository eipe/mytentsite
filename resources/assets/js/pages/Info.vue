<template>
    <div class="has-fixed-header">
        <section class="hero is-fullheight is-fullheight-excluding-navbar" style="background: url('/images/tentsite.jpg'); background-position: top center; background-repeat: no-repeat; background-size: cover">
            <div class="hero-head">
                <div class="container">
                    <br><br>
                    <transition enter-active-class="animated fadeIn">
                        <nav class="level" v-if="isLoaded" v-cloak>
                            <div class="level-item has-text-centered">
                                <router-link to="share">
                                    <p class="title">{{ $t('action.share')}}<i class="fa fa-camera icon is-large"></i></p>
                                    <p class="heading has-text-black">{{ $tc('share.title')}}</p>
                                </router-link>
                            </div>
                            <div class="level-item has-text-centered">
                                <router-link to="locate">
                                    <p class="title">{{ $t('action.locate')}}<i class="fa fa-map icon is-large"></i></p>
                                    <p class="heading has-text-black">{{ $tc('locate.title')}}</p>
                                </router-link>
                            </div>
                            <div class="level-item has-text-centered">
                                <router-link to="explore">
                                    <p class="title">{{ $t('action.explore')}}<i class="fa fa-th icon is-large"></i></p>
                                    <p class="heading has-text-black">{{ $tc('explore.title')}}</p>
                                </router-link>
                            </div>
                        </nav>
                    </transition>
                </div>
            </div>
        </section>
        <section class="hero is-small is-light">
            <div class="hero-body">
                <div class="container">
                    <transition enter-active-class="animated fadeIn">
                        <div class="columns is-mobile" v-show="isLoaded" v-cloak>
                            <div class="column has-text-centered">
                                <p class="title is-marginless is-paddingless">{{ statistics.numberOfTentSites }}</p>
                                <p class="heading">{{ $t('info.tentSites')}}</p>
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
        <section class="hero is-medium is-light">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title is-2">{{ $t('spreadTheWord')}}</h1>
                    <h2 class="subtitle is-4">- {{ $t('spreadTheWordSub')}}</h2>
                    <p>
                        {{ $t('spreadTheWordBody')}}
                    </p>
                </div>
            </div>
        </section>
        <section class="hero is-light">
            <div class="hero-body">
                <div class="container">
                    <div class="columns">
                        <div class="column is-offset-1 is-4" style="background-image: url('/images/tentsite-share.jpg'); background-size: cover">
                            <figure class="image is-4by3"></figure>
                        </div>
                        <div class="column is-6 notification is-white" style="padding: 40px">
                            <h1 class="title is-3">{{ $t('share.title')}}</h1>
                            <article class="media">
                                <div class="media-left">
                                    <i class="fa fa-2x fa-camera"></i>
                                </div>
                                <div class="media-content">
                                    <div class="field">
                                    <p class="control">
                                        {{ $t('share.guide.stepOneTitle')}}
                                    </p>
                                    </div>
                                </div>
                            </article>
                            <article class="media">
                                <div class="media-left">
                                    <i class="fa fa-2x fa-pencil"></i>
                                </div>
                                <div class="media-content">
                                    <div class="field">
                                    <p class="control">
                                        {{ $t('share.guide.stepTwoTitle')}}
                                    </p>
                                    </div>
                                </div>
                            </article>
                            <article class="media">
                                <div class="media-left">
                                    <i class="fa fa-2x fa-eye"></i>
                                </div>
                                <div class="media-content">
                                    <div class="field">
                                    <p class="control">
                                        {{ $t('share.guide.stepThreeTitle')}}
                                    </p>
                                    </div>
                                </div>
                            </article>
                            <article class="media">
                                <div class="media-left">
                                    <i class="fa fa-2x fa-upload"></i>
                                </div>
                                <div class="media-content">
                                    <div class="field">
                                    <p class="control">
                                        {{ $t('share.guide.stepFourTitle')}}
                                    </p>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="hero is-info">
            <div class="hero-body">
                <div class="container">
                    <transition enter-active-class="animated fadeIn">
                        <nav class="level" v-if="isLoaded" v-cloak>
                            <div class="level-item has-text-centered">
                                <router-link to="share">
                                    <p class="title">{{ $t('action.share')}}<i class="fa fa-camera icon is-large"></i></p>
                                    <p class="heading">{{ $tc('share.title')}}</p>
                                </router-link>
                            </div>
                            <div class="level-item has-text-centered">
                                <router-link to="locate">
                                    <p class="title">{{ $t('action.locate')}}<i class="fa fa-map icon is-large"></i></p>
                                    <p class="heading">{{ $tc('locate.title')}}</p>
                                </router-link>
                            </div>
                            <div class="level-item has-text-centered">
                                <router-link to="explore">
                                    <p class="title">{{ $t('action.explore')}}<i class="fa fa-th icon is-large"></i></p>
                                    <p class="heading">{{ $tc('explore.title')}}</p>
                                </router-link>
                            </div>
                        </nav>
                    </transition>
                </div>
            </div>
        </section>
        <section class="hero is-medium is-light">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title is-2">{{ $t('care.title')}}</h1>
                    <div class="columns">
                        <div class="column is-8 is-offset-2" v-html="$t('care.body')">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>

    export default {
        name: "Info",
        i18n: {
            messages: {
                en: {
                    info: {
                        tentSites: 'Shared tent sites',
                        countries: 'Countries represented',
                        contributors: 'Contributors',
                    },
                    spreadTheWord: 'Spread the word and inspire others',
                    spreadTheWordSub: 'to share their favourite tent sites!',
                    spreadTheWordBody: 'We encourage you to bring this message further on, in hope of a growing\n' +
                        'database of tent sites throughout the world. We will promote tent sites through our\n' +
                        'instagram account on request, to spread the word and inspire to share.',
                    share: {
                        title: 'Share your favourites with others',
                        guide: {
                            stepOneTitle: 'Select a photo with location and adjust it like you want',
                            stepTwoTitle: 'Describe to other campers why you like this tent site and include some useful information',
                            stepThreeTitle: 'Preview your tent site and review the details before you share the tent site',
                            stepFourTitle: 'Share your tent site and continue expore our grate nature. We will review your contribution before it is published',
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
                    spreadTheWord: 'Spre ordet, og inspirer andre',
                    spreadTheWordSub: 'til å dele deres favoritt teltplasser!',
                    spreadTheWordBody: 'Vi oppfordrer deg til å spre ordet, i håp om en stadig voksende samling av teltplasser rundt om i verden.',
                    share: {
                        title: 'Del dine favoritter med andre',
                        guide: {
                            stepOneTitle: 'Velg ett foto med lokasjon og juster bildet slik du vil ha det',
                            stepTwoTitle: 'Fortell andre naturnytere hvorfor du liker denne teltplassen og litt nyttig informasjon',
                            stepThreeTitle: 'Forhåndsvis og se over detaljene før du deler teltplassen',
                            stepFourTitle: 'Del teltplassen og fortsett å utforske naturen. Vi vil se over og godkjenne før det publiseres',
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
    }
</script>
