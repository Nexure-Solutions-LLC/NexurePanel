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

    enum userRole {
        
        case Administrator;
        case Customer;
        case AuthorizedUser;
        case Partner;

        public static function fromString(string $roleName): ?self {

            foreach (self::cases() as $case) {

                if ($case->name === $roleName) {

                    return $case;

                }

            }

            return null;

        }

    }

    enum accountStatus {
        case Active;
        case UnderReview;
        case Closed;
        case Suspended;
        case Terminated;
        case Restricted;
    }

    enum statusColor: string {
        case Active = "green";
        case UnderReview = "yellow";
        case Closed = "passive";
        case Suspended = "red";
        case Terminated = "red-dark";
        case Restricted = "red-restricted";
    }

    enum taskStatus
    {
        case Completed;
        case Pending;
        case Closed;
        case OverDue;
        case Stuck;
    }

    enum priorityLevel {
        case Highest;
        case Elevated;
        case Normal;
    }

?>