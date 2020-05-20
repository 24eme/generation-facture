\documentclass[a4paper, 12pt]{article}
\documentclass[a4paper, 10pt]{letter}
\usepackage[utf8]{inputenc}
\usepackage[T1]{fontenc}
\usepackage[francais]{babel}
\usepackage[top=3.5cm, bottom=3cm, left=1cm, right=1cm, headheight=5.5cm, headsep=0mm, marginparwidth=0cm]{geometry}
\usepackage{fancyhdr}
\usepackage{graphicx}
\usepackage[table]{xcolor}
\usepackage{units}
\usepackage{fp}
\usepackage{tikz}
\usepackage{array}
\usepackage{multicol}
\usepackage{textcomp}
\usepackage{marvosym}
\usepackage{lastpage}
\usepackage{truncate}
\usepackage{colortbl}
\usepackage{tabularx}
\usepackage{multirow}
\usepackage{hhline}
\usepackage{longfbox}

\definecolor{noir}{rgb}{0,0,0}
\definecolor{blanc}{rgb}{1,1,1}
\definecolor{verttresclair}{rgb}{0.90,0.90,0.90}
\definecolor{vertclair}{rgb}{0.70,0.70,0.70}
\definecolor{vertfonce}{rgb}{0.17,0.29,0.28}
\definecolor{vertmedium}{rgb}{0.63,0.73,0.22}



\def\LOGO{logopath}
\def\FACTUREDATE{01/01/1970}
\def\FACTURERIB{}
\def\FACTUREIBAN{}
\def\FACTUREBIC{}
\def\FACTURENUMERO{}

\def\FACTUREREGLEMENT{Le paiement doit être effectué dans les 30 jours suivant la réception de la facture, au choix par chèque ou virement bancaire.
Tout retard de paiement entraînera des pénalités dues de plein droit, égales au taux d'intérêt appliqué par la Banque Centrale
Européenne à son opération de refinancement la plus récente majoré de 10 points de pourcentage. Les pénalités de retard sont
exigibles sans qu'un rappel soit nécessaire (Article L441-6 du Code de commerce). Indemnité forfaitaire pour frais de recouvrement
de 40 € due en cas de retard de paiement (Art. D441-5 Code commerce) \\ ~ \\}


\def\COMPAGNYNAME{}
\def\COMPAGNYTYPE{}
\def\COMPAGNYTYPE2{}
\def\COMPAGNYADRESS{}
\def\COMPAGNYCP{}
\def\COMPAGNYCITY{}
\def\COMPAGNYEMAIL{}

\def\CLIENTNAME{}
\def\CLIENTADRESS{}
\def\CLIENTCP{}
\def\CLIENTCITY{}


\def\FACTURETOTALHT{0.0}
\def\FACTURETOTALTVA{0.0}
\def\FACTURETOTALTTC{0.0}


\pagestyle{fancy}
\pagenumbering{gobble}
\renewcommand{\headrulewidth}{0pt}

\setlength{\textheight}{18.5cm}


\fancyhead[L]{\includegraphics[width=3cm]{\LOGO}}
\fancyhead[R]{À Paris, le \FACTUREDATE \vspace{2cm}}
\fancyfoot[LO]{\textbf{RÈGLEMENT}\\~\\
\FACTUREREGLEMENT
\textbf{RIB : } \FACTURERIB\\
\textbf{IBAN : } \FACTUREIBAN\\
\textbf{BIC : } \FACTUREBIC}





\begin{document}
\begin{minipage}{0.5\textwidth}
\textbf{\COMPAGNYNAME \\ \COMPAGNYTYPE \\ \COMPAGNYTYPE2 \\ \textbf{\COMPAGNYADRESS} \\ \textbf{\COMPAGNYCP \COMPAGNYCITY} \\ Email : \COMPAGNYEMAIL \\
\end{minipage}
\begin{minipage}{0.5\textwidth}
\flushright{\textbf{
\CLIENTNAME \\ \CLIENTADRESS \\ \CLIENTCP \\ \CLIENTCITY }}
\end{minipage}

\begin{minipage}{0.5\textwidth}
\textbf{\\N° SIREN : 810 720 557} \\
Immatriculation : 810 720 557 R.C.S Paris\\
N° TVA Intracommunautaire : FR76 810720557\\
Code NAF : 6201Z \\
\end{minipage}
\begin{minipage}{0.5\textwidth}
\end{minipage}

\flushright{\textbf{Le \DATE,}}
\vspace{1cm}
\flushleft{\large{\textbf{FACTURE N° \FACTURENUMERO}}}
\vspace{1cm}


\renewcommand{\arraystretch}{1.5}
\arrayrulecolor{vertclair}
\begin{tabular}{|m{9.98cm}|>{\raggedleft}m{1.5cm}|>{\raggedleft}m{2.1cm}|>{\raggedleft}m{1.9cm}|}
  \hline
  \rowcolor{verttresclair} \textbf{Prestations} & \multicolumn{1}{c|}{\textbf{Temps (jH)}} & \multicolumn{1}{c|}{\textbf{Tarif HT (€ / jH)}} &  \multicolumn{1}{c|}{\textbf{Total HT}}  \tabularnewline
  \hline
    A & B & C & D
   \tabularnewline
\hline
\end{tabular}

\vspace{0.5cm}


\flushright{
\renewcommand{\arraystretch}{1.5}
\arrayrulecolor{vertclair}
\begin{tabular}{m{2.1cm}|>{\raggedleft}m{3.8cm}|>{\raggedleft}m{2.2cm}|}
  \hhline{|~|-|-}
  & \cellcolor{verttresclair} \textbf{TOTAL HT} & \textbf{\FACTURETOTALHT~€} \tabularnewline
  \hhline{|~|-|-}
  & \cellcolor{verttresclair} \textbf{TOTAL TVA 20\%}  & \textbf{\FACTURETOTALTVA~€} \tabularnewline
  \hhline{|~|-|-}
  & \cellcolor{verttresclair} \textbf{NET À PAYER}  & \textbf{\FACTURETOTALTTC~€} \tabularnewline
  \hhline{|~|-|-}
\end{tabular}
}


\end{document}
