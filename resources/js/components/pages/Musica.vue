<template>
    <div class="text-center px-2" style="max-width: 100%; width: 100%;"> 
        <h1 style="font-family: Bungee; ">HAGAMOS JUNTOS </h1>
        <h1 style="font-family: Bungee; ">LA PLAYLIST PERFECTA!!!</h1>

        <span>
            <h3>
                Queremos que nuestra boda sea la mejor fiesta posible para todos. Por eso os invitamos a que 
                propongáis canciones que os gustaría escuchar y al mismo tiempo votéis las canciones ya propuestas.
            </h3>
        </span>
        <span>
            <h2>
                ¡Cuantas más canciones propongáis y votéis, mejor será la fiesta!
            </h2>
        </span>

        <CancionesBuscador />

        <CancionesTable />
    </div>
</template>

<script>
import CancionesBuscador from './Musica/CancionesBuscador.vue';
import CancionesTable from './Musica/CancionesTable.vue';

export default {
    components: { CancionesBuscador, CancionesTable }
}
</script>
