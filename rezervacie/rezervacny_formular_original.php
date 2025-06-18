<?php
// rezervacny_formular_original.php
// Tento súbor obsahuje presne pôvodný HTML formulár pre rezerváciu stola, bez akýchkoľvek úprav.
// Môžeš ho vkladať na akúkoľvek stránku pomocou include 'rezervacny_formular_original.php';
?>

<form id="form-submit" action="rezervacie/rezervuj.php" method="post">
    <section id="book-table">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Booknite si stôl</h2>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-2 col-sm-12">
                    <div class="left-image">
                        <img src="img/book_left_image.jpg" alt="">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="right-info">
                        <h4>Rezervácia</h4>
                        <form id="form-submit" action="" method="get">
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset>
                                        <select required name='day' onchange='this.form.()'>
                                            <option value="">Vyberte si deň</option>
                                            <option value="2025-16-06">Pondelok (16.6.2025)</option>
                                            <option value="2025-17-06">Utorok (17.6.2025)</option>
                                            <option value="2025-18-06">Streda (18.6.2025)</option>
                                            <option value="2025-19-06">Štvrtok (19.6.2025)</option>
                                            <option value="2025-20-06">Piatok (20.6.2025)</option>
                                            <option value="2025-21-06">Sobota (21.6.2025)</option>
                                            <option value="2025-22-06">Nedeľa (22.6.2025)</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <select required name='hour' onchange='this.form.()'>
                                            <option value="">Vyberte si hodinu</option>
                                            <option value="10-00">10:00</option>
                                            <option value="12-00">12:00</option>
                                            <option value="14-00">14:00</option>
                                            <option value="16-00">16:00</option>
                                            <option value="18-00">18:00</option>
                                            <option value="20-00">20:00</option>
                                            <option value="22-00">22:00</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <input name="name" type="name" class="form-control" id="name" placeholder="Meno a priezvisko" required="">
                                    </fieldset> 
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <input name="phone" type="phone" class="form-control" id="phone" placeholder="Telefónne číslo" required="">
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <select required class="person" name='persons' onchange='this.form.()'>
                                            <option value="">Koľko ľudí?</option>
                                            <option value="1-osoba">1 </option>
                                            <option value="2-osoby">2 </option>
                                            <option value="3-osoby">3 </option>
                                            <option value="4-osoby">4 </option>
                                            <option value="5-osoby">5 </option>
                                            <option value="6-osoby">6 </option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <button type="submit" id="form-submit" class="btn">Booknite si stôl</button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form> 