@extends('layouts.dashboard')

@section('title', 'Nouveau devis')

@section('content')
    <div class="max-w-6xl space-y-6">
        <h1 class="text-2xl font-semibold text-[#1F2937]">Nouveau devis</h1>
        <form method="post" action="{{ route('devis.store') }}" enctype="multipart/form-data" class="space-y-4 rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            @csrf
            
            <!-- Client and Basic Info Section -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="client_id" class="block text-sm font-medium text-[#374151]">Client *</label>
                    <select name="client_id" id="client_id" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        <option value="">Choisir un client</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->nom_raison_sociale }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="date" class="block text-sm font-medium text-[#374151]">Date *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="tva" class="block text-sm font-medium text-[#374151]">TVA (%)</label>
                    <input type="number" name="tva" id="tva" value="{{ old('tva', 20) }}" step="0.01" min="0" max="100" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('tva') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="disponibilite" class="block text-sm font-medium text-[#374151]">Disponibilité</label>
                    <select name="disponibilite" id="disponibilite" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        <option value="">-- Choisir --</option>
                        @foreach(['Immédiate','1 jour','2 jours','3 jours','4 jours','5 jours','1 semaine','2 semaines','3 semaines','4 semaines','8 semaines','12 semaines','16 semaines'] as $opt)
                            <option value="{{ $opt }}" {{ old('disponibilite') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                    @error('disponibilite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="signature_image" class="block text-sm font-medium text-[#374151]">Signature/Photo du devis</label>
                    <input type="file" name="signature_image" id="signature_image" accept="image/*" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    <p class="text-xs text-[#6B7280] mt-1">JPG, PNG, GIF (max 5 MB)</p>
                    @error('signature_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Article Selection Section -->
            <div class="border-t border-[#E5E7EB] pt-6">
                <h3 class="text-lg font-semibold text-[#1F2937] mb-4">Ajouter des articles</h3>
                
                <div class="space-y-4 mb-4">
                    <!-- Row 1: Article Selection or Manual Name -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="article_select" class="block text-sm font-medium text-[#374151]">Sélectionner un article existant</label>
                            <select id="article_select" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                <option value="">-- Choisir un article --</option>
                                @foreach($articles as $article)
                                    <option value="{{ $article->id }}" data-name="{{ $article->nom }}" data-price="{{ $article->prix_vente }}" data-image="{{ $article->image ? asset('storage/' . $article->image) : '' }}" data-reference="{{ $article->ugs ?? '' }}">
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
                            <label for="reference_input" class="block text-sm font-medium text-[#374151]">Référence</label>
                            <input type="text" id="reference_input" placeholder="Réf." class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        </div>
                        
                        <div>
                            <label for="quantite_input" class="block text-sm font-medium text-[#374151]">Quantité</label>
                            <input type="number" id="quantite_input" step="1" min="1" value="1" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        </div>
                        
                        <div>
                            <label for="prix_unitaire_input" class="block text-sm font-medium text-[#374151]">Prix unitaire (DH)</label>
                            <input type="number" id="prix_unitaire_input" step="0.01" min="0" placeholder="0.00" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="button" id="add_article_btn" class="w-full rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">
                                Ajouter
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
                                <th class="px-4 py-2 text-left font-medium text-[#374151]">Référence</th>
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
                <input type="hidden" id="articles_data" name="articles_data" value="">

                <!-- Totals Section -->
                <div class="mt-8 bg-gradient-to-br from-[#F3F4F6] to-[#E5E7EB] rounded-lg p-6 border border-[#E5E7EB]">
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Total HT Card -->
                        <div class="bg-white rounded-lg p-4 border-l-4 border-[#1860E1] shadow-sm">
                            <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Total HT</p>
                            <p class="mt-2 text-2xl font-bold text-[#1F2937]"><span id="total_ht">0.00</span></p>
                            <p class="text-xs text-[#9CA3AF] mt-1">DH</p>
                        </div>

                        <!-- TVA Card -->
                        <div class="bg-white rounded-lg p-4 border-l-4 border-[#F97316] shadow-sm">
                            <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">TVA</p>
                            <p class="mt-2 text-2xl font-bold text-[#1F2937]"><span id="total_tva">0.00</span></p>
                            <p class="text-xs text-[#9CA3AF] mt-1">DH</p>
                        </div>

                        <!-- Total TTC Card -->
                        <div class="bg-gradient-to-br from-[#1860E1] to-[#1557C7] rounded-lg p-4 border-l-4 border-[#0F3D8F] shadow-md">
                            <p class="text-xs font-medium text-blue-100 uppercase tracking-wide">Total TTC</p>
                            <p class="mt-2 text-2xl font-bold text-white"><span id="total_ttc">0.00</span></p>
                            <p class="text-xs text-blue-200 mt-1">DH</p>
                        </div>
                    </div>

                    <!-- Summary Line -->
                    <div class="mt-4 pt-4 border-t border-[#E5E7EB] flex justify-between items-center text-sm">
                        <span class="text-[#6B7280]">TVA (<span id="tva_rate">20</span>%)</span>
                        <span class="text-[#1F2937] font-semibold">+ <span id="total_tva_inline">0.00</span> DH</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 border-t border-[#E5E7EB] pt-6">
                <button type="submit" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">Créer le devis</button>
                <a href="{{ route('devis.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">Annuler</a>
            </div>
        </form>
    </div>

    <script>
        let articles = [];
        let tvaRate = parseFloat(document.getElementById('tva').value) / 100;

        // Show article preview when selected
        document.getElementById('article_select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const preview = document.getElementById('article_preview');
            const designationInput = document.getElementById('designation_input');
            const prixUnitaireInput = document.getElementById('prix_unitaire_input');
            const referenceInput = document.getElementById('reference_input');
            
            if (selectedOption.value) {
                // Fill price and reference automatically when article is selected
                prixUnitaireInput.value = selectedOption.dataset.price;
                referenceInput.value = selectedOption.dataset.reference || '';
                
                // Clear manual designation input
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

        // Clear article selection when manual designation is entered
        document.getElementById('designation_input').addEventListener('input', function() {
            if (this.value) {
                document.getElementById('article_select').value = '';
                document.getElementById('article_preview').classList.add('hidden');
                // Don't clear reference - user may want to manually enter it
            }
        });

        document.getElementById('add_article_btn').addEventListener('click', function() {
            const select = document.getElementById('article_select');
            const designationInput = document.getElementById('designation_input');
            const referenceInput = document.getElementById('reference_input');
            const quantiteInput = document.getElementById('quantite_input');
            const prixUnitaireInput = document.getElementById('prix_unitaire_input');
            const selectedOption = select.options[select.selectedIndex];
            
            // Check if either article is selected OR manual designation is provided
            let article;
            
            if (selectedOption.value) {
                // Use selected article
                article = {
                    id: selectedOption.value,
                    designation: selectedOption.dataset.name,
                    reference: selectedOption.dataset.reference || '',
                    prix_unitaire: parseFloat(selectedOption.dataset.price),
                    quantite: parseFloat(quantiteInput.value),
                    image: selectedOption.dataset.image,
                };
            } else if (designationInput.value && prixUnitaireInput.value) {
                // Use manual input
                article = {
                    id: null, // No ID for manual articles
                    designation: designationInput.value,
                    reference: referenceInput.value || '',
                    prix_unitaire: parseFloat(prixUnitaireInput.value),
                    quantite: parseFloat(quantiteInput.value),
                    image: null,
                };
            } else {
                alert('Veuillez soit sélectionner un article, soit saisir une désignation et un prix');
                return;
            }

            article.total_ht = (article.quantite * article.prix_unitaire).toFixed(2);
            
            articles.push(article);
            updateTable();
            
            // Reset inputs
            select.value = '';
            designationInput.value = '';
            referenceInput.value = '';
            prixUnitaireInput.value = '';
            quantiteInput.value = '1';
            document.getElementById('article_preview').classList.add('hidden');
        });

        function removeArticle(index) {
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
                    <td class="px-4 py-3 text-[#6B7280] text-sm">${article.reference || '-'}</td>
                    <td class="px-4 py-3 text-[#374151]">${article.designation}</td>
                    <td class="px-4 py-3 text-right text-[#374151]">${parseFloat(article.quantite).toFixed(2)}</td>
                    <td class="px-4 py-3 text-right text-[#374151]">${parseFloat(article.prix_unitaire).toFixed(2)}</td>
                    <td class="px-4 py-3 text-right text-[#374151] font-medium">${parseFloat(article.total_ht).toFixed(2)}</td>
                    <td class="px-4 py-3 text-center">
                        <button type="button" onclick="removeArticle(${index})" class="text-red-600 hover:text-red-800 text-sm font-medium">Supprimer</button>
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

            // Store articles data as JSON for form submission
            document.getElementById('articles_data').value = JSON.stringify(articles);
        }

        // Update totals when TVA changes
        document.getElementById('tva').addEventListener('change', function() {
            tvaRate = parseFloat(this.value) / 100;
            document.getElementById('tva_rate').textContent = this.value;
            updateTable();
        });
    </script>
@endsection
