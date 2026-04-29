@extends('layouts.dashboard')

@section('title', 'Modifier la facture ' . $facture->numero)

@section('content')
    @php
        $articleImages = $articles->mapWithKeys(function ($article) {
            return [$article->nom => $article->image ? asset('storage/' . $article->image) : ''];
        })->toArray();
        $articleIds = $articles->mapWithKeys(function ($article) {
            return [$article->nom => $article->id];
        })->toArray();
        $articleCategories = $articles->mapWithKeys(function ($article) {
            return [$article->nom => $article->categorie->nom ?? ''];
        })->toArray();
        $initialArticles = $facture->lignes->map(function ($ligne) use ($articleImages, $articleIds, $articleCategories) {
            return [
                'id' => $articleIds[$ligne->designation] ?? null,
                'designation' => $ligne->designation,
                'categorie' => $ligne->categorie ?? ($articleCategories[$ligne->designation] ?? ''),
                'prix_unitaire' => $ligne->prix_unitaire,
                'quantite' => $ligne->quantite,
                'image' => $articleImages[$ligne->designation] ?? '',
                'total_ht' => $ligne->total_ht,
            ];
        })->values()->toArray();
    @endphp

    <div class="max-w-6xl space-y-6">
        <h1 class="text-2xl font-semibold text-[#1F2937]">Modifier la facture {{ $facture->numero }}</h1>
        <form method="post" action="{{ route('factures.update', $facture) }}" class="space-y-4 rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <!-- Client and Basic Info Section -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="numero" class="block text-sm font-medium text-[#374151]">Numéro *</label>
                    <input type="text" name="numero" id="numero" value="{{ old('numero', $facture->numero) }}" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('numero') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-[#374151]">Date *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $facture->date->format('Y-m-d')) }}" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="client_id" class="block text-sm font-medium text-[#374151]">Client *</label>
                <select name="client_id" id="client_id" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    <option value="">Choisir un client</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ old('client_id', $facture->client_id) == $c->id ? 'selected' : '' }}>{{ $c->nom_raison_sociale }}</option>
                    @endforeach
                </select>
                @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="date_echeance" class="block text-sm font-medium text-[#374151]">Date d'échéance</label>
                    <input type="date" name="date_echeance" id="date_echeance" value="{{ old('date_echeance', $facture->date_echeance?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('date_echeance') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tva" class="block text-sm font-medium text-[#374151]">TVA (%)</label>
                    <input type="number" name="tva" id="tva" value="{{ old('tva', $facture->tva) }}" step="0.01" min="0" max="100" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('tva') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="montant_paye" class="block text-sm font-medium text-[#374151]">Montant payé (DH)</label>
                    <input type="number" name="montant_paye" id="montant_paye" value="{{ old('montant_paye', $facture->montant_paye) }}" step="0.01" min="0" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('montant_paye') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="statut" class="block text-sm font-medium text-[#374151]">Statut</label>
                <select name="statut" id="statut" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @foreach(['payee','non_payee','partiellement_payee'] as $s)
                        <option value="{{ $s }}" {{ old('statut', $facture->statut) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('statut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Article Selection Section -->
            <div class="border-t border-[#E5E7EB] pt-6">
                <h3 class="text-lg font-semibold text-[#1F2937] mb-4">Articles de la facture</h3>

                <div class="space-y-4 mb-4">
                    <!-- Row 1: Article Selection or Manual Name -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="article_select" class="block text-sm font-medium text-[#374151]">Sélectionner un article existant</label>
                            <select id="article_select" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                <option value="">-- Choisir un article --</option>
                                @foreach($articles as $article)
                                    <option value="{{ $article->id }}" data-name="{{ $article->nom }}" data-price="{{ $article->prix_vente }}" data-image="{{ $article->image ? asset('storage/' . $article->image) : '' }}" data-categorie="{{ $article->categorie->nom ?? '' }}">
                                        {{ $article->nom }} ({{ $article->prix_vente }} DH)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="designation_input" class="block text-sm font-medium text-[#374151]">OU Saisir manuellement la désignation</label>
                            <input type="text" id="designation_input" placeholder="Ex: Nouveau produit" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        </div>
                    </div>
                    
                    <!-- Row 2: Quantity and Price -->
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <label for="categorie_input" class="block text-sm font-medium text-[#374151]">Catégorie</label>
                            <input type="text" id="categorie_input" placeholder="Catégorie" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        </div>
                        
                        <div>
                            <label for="quantite_input" class="block text-sm font-medium text-[#374151]">Quantité</label>
                            <input type="number" id="quantite_input" step="1" min="1" value="1" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        </div>
                        
                        <div>
                            <label for="prix_unitaire_input" class="block text-sm font-medium text-[#374151]">Prix unitaire (DH)</label>
                            <input type="number" id="prix_unitaire_input" step="0.01" min="0" placeholder="0.00" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        </div>
                        
                        <div class="flex items-end gap-2">
                            <button type="button" id="add_article_btn" class="flex-1 rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">
                                <span id="add_btn_text">Ajouter</span>
                            </button>
                            <button type="button" id="cancel_edit_btn" class="hidden rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Article Preview -->
                <div id="article_preview" class="mb-4 p-4 rounded-lg bg-[#F3F4F6] border border-[#E5E7EB] hidden">
                    <p class="text-sm font-medium text-[#374151] mb-2">Aperçu:</p>
                    <div class="flex items-center gap-4">
                        <img id="preview_image" src="" alt="Article" class="h-16 w-16 rounded-lg object-cover border border-[#E5E7EB]">
                        <div>
                            <p id="preview_name" class="font-medium text-[#1F2937]"></p>
                            <p id="preview_price" class="text-sm text-[#6B7280]"></p>
                        </div>
                    </div>
                </div>

                <!-- Articles Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                            <tr>
                                <th class="px-4 py-2 text-center font-medium text-[#374151]" style="width: 60px;">Image</th>
                                <th class="px-4 py-2 text-left font-medium text-[#374151]">Catégorie</th>
                                <th class="px-4 py-2 text-left font-medium text-[#374151]">Désignation</th>
                                <th class="px-4 py-2 text-right font-medium text-[#374151]">Quantité</th>
                                <th class="px-4 py-2 text-right font-medium text-[#374151]">Prix unitaire</th>
                                <th class="px-4 py-2 text-right font-medium text-[#374151]">Total HT</th>
                                <th class="px-4 py-2 text-center font-medium text-[#374151]">Action</th>
                            </tr>
                        </thead>
                        <tbody id="articles_tbody">
                            <!-- Articles will be added here by JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Hidden input to store articles data -->
                <input type="hidden" id="articles_data" name="articles_data" value='@json($initialArticles ?? [])'>

                <!-- Totals Section -->
                <div class="mt-8 bg-gradient-to-br from-[#F3F4F6] to-[#E5E7EB] rounded-lg p-6 border border-[#E5E7EB]">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg p-4 border-l-4 border-[#1860E1] shadow-sm">
                            <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Total HT</p>
                            <p class="mt-2 text-2xl font-bold text-[#1F2937]"><span id="total_ht">0.00</span></p>
                            <p class="text-xs text-[#9CA3AF] mt-1">DH</p>
                        </div>

                        <div class="bg-white rounded-lg p-4 border-l-4 border-[#F97316] shadow-sm">
                            <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">TVA</p>
                            <p class="mt-2 text-2xl font-bold text-[#1F2937]"><span id="total_tva">0.00</span></p>
                            <p class="text-xs text-[#9CA3AF] mt-1">DH</p>
                        </div>

                        <div class="bg-gradient-to-br from-[#1860E1] to-[#1557C7] rounded-lg p-4 border-l-4 border-[#0F3D8F] shadow-md">
                            <p class="text-xs font-medium text-blue-100 uppercase tracking-wide">Total TTC</p>
                            <p class="mt-2 text-2xl font-bold text-white"><span id="total_ttc">0.00</span></p>
                            <p class="text-xs text-blue-200 mt-1">DH</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-[#E5E7EB] flex justify-between items-center text-sm">
                        <span class="text-[#6B7280]">TVA (<span id="tva_rate">{{ old('tva', $facture->tva) }}</span>%)</span>
                        <span class="text-[#1F2937] font-semibold">+ <span id="total_tva_inline">0.00</span> DH</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 border-t border-[#E5E7EB] pt-6">
                <button type="submit" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">Enregistrer</button>
                <a href="{{ route('factures.show', $facture) }}" class="rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">Annuler</a>
            </div>
        </form>
    </div>

    <script>
        let articles = @json($initialArticles);
        let editingIndex = null;

        let tvaRate = parseFloat(document.getElementById('tva').value || '0') / 100;

        document.getElementById('article_select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const preview = document.getElementById('article_preview');
            const designationInput = document.getElementById('designation_input');
            const prixUnitaireInput = document.getElementById('prix_unitaire_input');
            const categorieInput = document.getElementById('categorie_input');

            if (selectedOption.value) {
                prixUnitaireInput.value = selectedOption.dataset.price;
                categorieInput.value = selectedOption.dataset.categorie || '';
                
                designationInput.value = '';
                
                const imageSrc = selectedOption.dataset.image;
                if (imageSrc) {
                    document.getElementById('preview_image').src = imageSrc;
                    document.getElementById('preview_image').style.display = 'block';
                } else {
                    document.getElementById('preview_image').style.display = 'none';
                }
                document.getElementById('preview_name').textContent = selectedOption.dataset.name;
                document.getElementById('preview_price').textContent = selectedOption.dataset.price + ' DH';
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });

        document.getElementById('designation_input').addEventListener('input', function() {
            if (this.value) {
                document.getElementById('article_select').value = '';
                document.getElementById('article_preview').classList.add('hidden');
            }
        });

        document.getElementById('add_article_btn').addEventListener('click', function() {
            const select = document.getElementById('article_select');
            const designationInput = document.getElementById('designation_input');
            const categorieInput = document.getElementById('categorie_input');
            const quantiteInput = document.getElementById('quantite_input');
            const prixUnitaireInput = document.getElementById('prix_unitaire_input');
            const selectedOption = select.options[select.selectedIndex];

            let article;
            
            if (selectedOption.value) {
                article = {
                    id: selectedOption.value,
                    designation: selectedOption.dataset.name,
                    categorie: selectedOption.dataset.categorie || '',
                    prix_unitaire: parseFloat(selectedOption.dataset.price),
                    quantite: parseFloat(quantiteInput.value),
                    image: selectedOption.dataset.image,
                };
            } else if (designationInput.value && prixUnitaireInput.value) {
                article = {
                    id: null,
                    designation: designationInput.value,
                    categorie: categorieInput.value || '',
                    prix_unitaire: parseFloat(prixUnitaireInput.value),
                    quantite: parseFloat(quantiteInput.value),
                    image: null,
                };
            } else {
                alert('Veuillez soit sélectionner un article, soit saisir une désignation et un prix');
                return;
            }

            article.total_ht = (article.quantite * article.prix_unitaire).toFixed(2);

            if (editingIndex !== null) {
                articles[editingIndex] = article;
                editingIndex = null;
                document.getElementById('add_btn_text').textContent = 'Ajouter';
                document.getElementById('cancel_edit_btn').classList.add('hidden');
            } else {
                articles.push(article);
            }
            
            updateTable();

            select.value = '';
            designationInput.value = '';
            categorieInput.value = '';
            prixUnitaireInput.value = '';
            quantiteInput.value = '1';
            document.getElementById('article_preview').classList.add('hidden');
        });

        document.getElementById('cancel_edit_btn').addEventListener('click', function() {
            editingIndex = null;
            document.getElementById('add_btn_text').textContent = 'Ajouter';
            document.getElementById('cancel_edit_btn').classList.add('hidden');
            
            document.getElementById('article_select').value = '';
            document.getElementById('designation_input').value = '';
            document.getElementById('categorie_input').value = '';
            document.getElementById('prix_unitaire_input').value = '';
            document.getElementById('quantite_input').value = '1';
            document.getElementById('article_preview').classList.add('hidden');
        });

        function editArticle(index) {
            const article = articles[index];
            editingIndex = index;
            
            document.getElementById('designation_input').value = article.designation;
            document.getElementById('categorie_input').value = article.categorie || '';
            document.getElementById('quantite_input').value = article.quantite;
            document.getElementById('prix_unitaire_input').value = article.prix_unitaire;
            
            if (article.id) {
                document.getElementById('article_select').value = article.id;
                
                if (article.image) {
                    document.getElementById('preview_image').src = article.image;
                    document.getElementById('preview_image').style.display = 'block';
                    document.getElementById('preview_name').textContent = article.designation;
                    document.getElementById('preview_price').textContent = article.prix_unitaire + ' DH';
                    document.getElementById('article_preview').classList.remove('hidden');
                }
            } else {
                document.getElementById('article_select').value = '';
                document.getElementById('article_preview').classList.add('hidden');
            }
            
            document.getElementById('add_btn_text').textContent = 'Mettre à jour';
            document.getElementById('cancel_edit_btn').classList.remove('hidden');
            
            window.scrollTo({
                top: document.getElementById('article_select').offsetTop - 100,
                behavior: 'smooth'
            });
        }

        function removeArticle(index) {
            if (editingIndex === index) {
                editingIndex = null;
                document.getElementById('add_btn_text').textContent = 'Ajouter';
                document.getElementById('cancel_edit_btn').classList.add('hidden');
                
                document.getElementById('article_select').value = '';
                document.getElementById('designation_input').value = '';
                document.getElementById('categorie_input').value = '';
                document.getElementById('prix_unitaire_input').value = '';
                document.getElementById('quantite_input').value = '1';
                document.getElementById('article_preview').classList.add('hidden');
            } else if (editingIndex !== null && editingIndex > index) {
                editingIndex--;
            }
            
            articles.splice(index, 1);
            updateTable();
        }

        function updateTable() {
            const tbody = document.getElementById('articles_tbody');
            tbody.innerHTML = '';

            let totalHt = 0;

            articles.forEach((article, index) => {
                const row = document.createElement('tr');
                row.className = 'border-b border-[#E5E7EB] hover:bg-[#F9FAFB]';
                const imageHtml = article.image
                    ? `<img src="${article.image}" alt="${article.designation}" class="h-10 w-10 rounded object-cover border border-[#E5E7EB]">`
                    : `<div class="h-10 w-10 rounded bg-[#F3F4F6] flex items-center justify-center"><svg class="w-5 h-5 text-[#D1D5DB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>`;

                row.innerHTML = `
                    <td class="px-4 py-3 text-center">${imageHtml}</td>
                    <td class="px-4 py-3 text-[#6B7280] text-sm">${article.categorie || '-'}</td>
                    <td class="px-4 py-3 text-[#374151]">${article.designation}</td>
                    <td class="px-4 py-3 text-right text-[#374151]">${parseFloat(article.quantite).toFixed(2)}</td>
                    <td class="px-4 py-3 text-right text-[#374151]">${parseFloat(article.prix_unitaire).toFixed(2)}</td>
                    <td class="px-4 py-3 text-right text-[#374151] font-medium">${parseFloat(article.total_ht).toFixed(2)}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button type="button" onclick="editArticle(${index})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Modifier</button>
                            <button type="button" onclick="removeArticle(${index})" class="text-red-600 hover:text-red-800 text-sm font-medium">Supprimer</button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
                totalHt += parseFloat(article.total_ht);
            });

            const totalTva = (totalHt * tvaRate).toFixed(2);
            const totalTtc = (totalHt + parseFloat(totalTva)).toFixed(2);

            document.getElementById('total_ht').textContent = totalHt.toFixed(2);
            document.getElementById('total_tva').textContent = totalTva;
            document.getElementById('total_tva_inline').textContent = totalTva;
            document.getElementById('total_ttc').textContent = totalTtc;

            document.getElementById('articles_data').value = JSON.stringify(articles);
        }

        document.getElementById('tva').addEventListener('change', function() {
            tvaRate = parseFloat(this.value || '0') / 100;
            document.getElementById('tva_rate').textContent = this.value;
            updateTable();
        });

        document.addEventListener('DOMContentLoaded', function() {
            updateTable();
        });
    </script>
@endsection
