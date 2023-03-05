<?php
declare(strict_types = 1);

namespace src\php;

final class Mapper
{
    public const GREEK_MAX_EXPONENT = 6;

    public const GREEK = [
        1 => 'α',
        2 => 'β',
        3 => 'γ',
        4 => 'δ',
        5 => 'ε',
        6 => 'ϛ',
        7 => 'ζ',
        8 => 'η',
        9 => 'θ',
        10 => 'ι',
        20 => 'κ',
        30 => 'λ',
        40 => 'μ',
        50 => 'ν',
        60 => 'ξ',
        70 => 'ο',
        80 => 'π',
        90 => 'ϟ',
        100 => 'ρ',
        200 => 'σ',
        300 => 'τ',
        400 => 'υ',
        500 => 'φ',
        600 => 'χ',
        700 => 'ψ',
        800 => 'ω',
        900 => 'ϡ',
        1000 => '͵α',
        2000 => '͵β',
        3000 => '͵γ',
        4000 => '͵δ',
        5000 => '͵ε',
        6000 => '͵ϛ',
        7000 => '͵ζ',
        8000 => '͵η',
        9000 => '͵θ',
        10000 => '͵ι',
        20000 => '͵κ',
        30000 => '͵λ',
        40000 => '͵μ',
        50000 => '͵ν',
        60000 => '͵ξ',
        70000 => '͵ο',
        80000 => '͵π',
        90000 => '͵ϟ',
        100000 => '͵ρ',
        200000 => '͵σ',
        300000 => '͵τ',
        400000 => '͵υ',
        500000 => '͵φ',
        600000 => '͵χ',
        700000 => '͵ψ',
        800000 => '͵ω',
        900000 => '͵ϡ',
    ];

    public const GREEK_NO_STIGMA = [
        1 => 'α',
        2 => 'β',
        3 => 'γ',
        4 => 'δ',
        5 => 'ε',
        6 => 'στ',
        7 => 'ζ',
        8 => 'η',
        9 => 'θ',
        10 => 'ι',
        20 => 'κ',
        30 => 'λ',
        40 => 'μ',
        50 => 'ν',
        60 => 'ξ',
        70 => 'ο',
        80 => 'π',
        90 => 'ϟ',
        100 => 'ρ',
        200 => 'σ',
        300 => 'τ',
        400 => 'υ',
        500 => 'φ',
        600 => 'χ',
        700 => 'ψ',
        800 => 'ω',
        900 => 'ϡ',
        1000 => '͵α',
        2000 => '͵β',
        3000 => '͵γ',
        4000 => '͵δ',
        5000 => '͵ε',
        6000 => '͵στ',
        7000 => '͵ζ',
        8000 => '͵η',
        9000 => '͵θ',
        10000 => '͵ι',
        20000 => '͵κ',
        30000 => '͵λ',
        40000 => '͵μ',
        50000 => '͵ν',
        60000 => '͵ξ',
        70000 => '͵ο',
        80000 => '͵π',
        90000 => '͵ϟ',
        100000 => '͵ρ',
        200000 => '͵σ',
        300000 => '͵τ',
        400000 => '͵υ',
        500000 => '͵φ',
        600000 => '͵χ',
        700000 => '͵ψ',
        800000 => '͵ω',
        900000 => '͵ϡ',
    ];

    public const GREEK_NO_KOPPA = [
        1 => 'α',
        2 => 'β',
        3 => 'γ',
        4 => 'δ',
        5 => 'ε',
        6 => 'στ',
        7 => 'ζ',
        8 => 'η',
        9 => 'θ',
        10 => 'ι',
        20 => 'κ',
        30 => 'λ',
        40 => 'μ',
        50 => 'ν',
        60 => 'ξ',
        70 => 'ο',
        80 => 'π',
        90 => 'πι',
        100 => 'ρ',
        200 => 'σ',
        300 => 'τ',
        400 => 'υ',
        500 => 'φ',
        600 => 'χ',
        700 => 'ψ',
        800 => 'ω',
        900 => 'ϡ',
        1000 => '͵α',
        2000 => '͵β',
        3000 => '͵γ',
        4000 => '͵δ',
        5000 => '͵ε',
        6000 => '͵στ',
        7000 => '͵ζ',
        8000 => '͵η',
        9000 => '͵θ',
        10000 => '͵ι',
        20000 => '͵κ',
        30000 => '͵λ',
        40000 => '͵μ',
        50000 => '͵ν',
        60000 => '͵ξ',
        70000 => '͵ο',
        80000 => '͵π',
        90000 => '͵πι',
        100000 => '͵ρ',
        200000 => '͵σ',
        300000 => '͵τ',
        400000 => '͵υ',
        500000 => '͵φ',
        600000 => '͵χ',
        700000 => '͵ψ',
        800000 => '͵ω',
        900000 => '͵ϡ',
    ];
}