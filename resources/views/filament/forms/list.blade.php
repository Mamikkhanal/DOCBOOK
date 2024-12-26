<div class="faq-section">
    <h2 class="text-xl font-bold mb-4">Frequently Asked Questions</h2>

    <div class="faq-list">
        @foreach ($data as $index => $faq)
            <div class="faq-item border-b border-gray-200 py-4">
                <button 
                    class="faq-question w-full text-left text-md font-medium text-gray-700 hover:text-blue-600 focus:outline-none"
                    onclick="toggleAnswer({{ $index }})"
                >
                    {{ $faq['pain'] }}
                </button>
                <div 
                    id="faq-answer-{{ $index }}" 
                    class="faq-answer mt-2 text-gray-600 hidden"
                >
                    <p>{{ $faq['specialization'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function toggleAnswer(index) {
        const answer = document.getElementById(`faq-answer-${index}`);
        if (answer.classList.contains('hidden')) {
            answer.classList.remove('hidden');
        } else {
            answer.classList.add('hidden');
        }
    }
</script>
