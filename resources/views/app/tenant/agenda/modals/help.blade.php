{{-- resources/views/app/tenant/agenda/modals/help.blade.php --}}
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">Agenda & Planning Help</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="agendaHelpAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Hoe navigeer ik door de agenda?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#agendaHelpAccordion">
                            <div class="accordion-body">
                                <p>De agenda biedt verschillende weergaven om u te helpen uw planning te beheren:</p>
                                <ul>
                                    <li><strong>Maand:</strong> Overzicht van alle events in een maand</li>
                                    <li><strong>Week:</strong> Gedetailleerde weekplanning</li>
                                    <li><strong>Dag:</strong> Gedetailleerde dagplanning per uur</li>
                                    <li><strong>Lijst:</strong> Bekijk alle events in een lijstweergave</li>
                                </ul>
                                <p>Gebruik de navigatiepijlen om naar verschillende perioden te gaan of klik op
                                    "Vandaag" om terug te keren naar de huidige datum.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Hoe voeg ik een nieuwe afspraak of inspectie toe?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#agendaHelpAccordion">
                            <div class="accordion-body">
                                <p>Er zijn meerdere manieren om een nieuwe afspraak toe te voegen:</p>
                                <ol>
                                    <li>Klik op de groene "Nieuw" knop rechtsboven</li>
                                    <li>Sleep een beschikbare inspectie direct naar de agenda</li>
                                    <li>Klik direct op een datum of tijdslot in de agenda</li>
                                </ol>
                                <p>Vul vervolgens alle benodigde gegevens in het formulier in en klik op "Opslaan".</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Hoe wijs ik taken toe aan teamleden?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                            data-bs-parent="#agendaHelpAccordion">
                            <div class="accordion-body">
                                <p>Om een taak toe te wijzen aan een teamlid:</p>
                                <ol>
                                    <li>Klik op de groene "Nieuw" knop en selecteer "Taak" als type</li>
                                    <li>Of sleep een teamlid direct naar een tijdslot in de agenda</li>
                                    <li>Vul de taakgegevens in, inclusief deadline en prioriteit</li>
                                    <li>Selecteer het gewenste teamlid uit de dropdown lijst</li>
                                    <li>Klik op "Taak toewijzen" om op te slaan</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Hoe bewerk of verwijder ik een bestaande afspraak?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                            data-bs-parent="#agendaHelpAccordion">
                            <div class="accordion-body">
                                <p>Om een afspraak te bewerken of te verwijderen:</p>
                                <ol>
                                    <li>Klik op de afspraak in de agenda</li>
                                    <li>In het popup venster, klik op "Bewerken" om wijzigingen aan te brengen</li>
                                    <li>Of klik op "Verwijderen" om de afspraak te verwijderen</li>
                                    <li>U kunt ook afspraken verslepen naar een nieuwe datum of tijd</li>
                                </ol>
                                <p><strong>Let op:</strong> Verwijderde afspraken kunnen niet worden hersteld.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Begrepen</button>
            </div>
        </div>
    </div>
</div>
