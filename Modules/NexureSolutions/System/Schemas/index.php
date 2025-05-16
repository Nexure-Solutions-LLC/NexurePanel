<?php

    enum RiskScoreCategory: string {
        case EXCELLENT = 'excellent';
        case VERY_GOOD = 'very_good';
        case GOOD = 'good';
        case FAIR = 'fair';
        case POOR = 'poor';

        public function label(): string {
            return match($this) {
                self::EXCELLENT => 'Excellent',
                self::VERY_GOOD => 'Very Good',
                self::GOOD => 'Good',
                self::FAIR => 'Fair',
                self::POOR => 'Poor',
            };
        }

        public function colorClass(): string {
            return match($this) {
                self::EXCELLENT, self::VERY_GOOD => 'green',
                self::GOOD => 'yellow',
                self::FAIR => 'orange',
                self::POOR => 'red',
            };
        }

        public static function fromScore(int $score): self {
            return match (true) {
                $score >= 700 => self::EXCELLENT,
                $score >= 600 => self::VERY_GOOD,
                $score >= 500 => self::GOOD,
                $score >= 300 => self::FAIR,
                default => self::POOR,
            };
        }
    }

?>