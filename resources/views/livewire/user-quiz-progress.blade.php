<div class="quiz-progress-card"
     style="background:#fff;border-radius:12px;padding:20px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
            margin:20px auto;max-width:500px;text-align:center;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;background:linear-gradient(135deg,#eef2ff,#faf5ff);opacity:0.6;"></div>

    <div style="position:relative;z-index:10;">
        <h3 style="color:#2b6cb0;margin-bottom:15px;font-size:1.2rem;font-weight:700;">
            📊 Statistiques en direct
        </h3>

        <div style="background:#e9ecef;border-radius:10px;height:10px;overflow:hidden;margin-bottom:10px;">
            <div style="height:10px;width:{{ $percentage }}%;
                        background:linear-gradient(90deg,#667eea,#764ba2);
                        transition:width 0.3s ease;"></div>
        </div>

        <div style="font-size:1.1rem;font-weight:600;color:#2d3748;margin-bottom:10px;">
            {{ $correctAnswers }}/{{ $totalQuestions }} correctes
        </div>

        <div style="font-size:2rem;font-weight:800;color:#38a169;margin-bottom:8px;">
            {{ $percentage }}%
        </div>

        @if($passed)
            <span style="background:#c6f6d5;color:#22543d;
                         padding:6px 12px;border-radius:8px;font-weight:600;">
                ✅ Vous êtes sur la bonne voie !
            </span>
        @elseif($percentage > 0)
            <span style="background:#fefcbf;color:#744210;
                         padding:6px 12px;border-radius:8px;font-weight:600;">
                💪 Continuez à progresser !
            </span>
        @else
            <span style="background:#edf2f7;color:#4a5568;
                         padding:6px 12px;border-radius:8px;font-weight:600;">
                🕐 Commencez à répondre
            </span>
        @endif
    </div>
</div>
