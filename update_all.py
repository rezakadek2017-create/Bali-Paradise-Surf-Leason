#!/usr/bin/env python3
"""
Comprehensive update to Bali Paradise Surf Lesson index.html:
1. Replace gallery with photos from Bali_Paradise_Surf_Lesson_foto_terpisah folder
2. Add Instructor Bios section
3. Replace Google Maps static image with live iframe embed
4. Add YouTube Videos section
5. Update gallery to use folder photos
6. Add social links (Instagram, Facebook, YouTube) to footer
"""

with open('index.html', 'r', encoding='utf-8') as f:
    content = f.read()

# ============================================================
# 1. ADD INSTRUCTOR BIOS SECTION (after About section, before Packages)
# ============================================================

instructors_section = '''<section class="section section--sand" id="team" aria-labelledby="team-title">
\t<div class="wrap">
\t\t<header class="section__head">
\t\t\t<h2 id="team-title">Meet Your Instructors</h2>
\t\t\t<p>Our team of local Balinese surfers brings years of experience, warmth, and deep ocean knowledge to every lesson.</p>
\t\t</header>
\t\t<div class="instructors">
\t\t\t<article class="instructor">
\t\t\t\t<div class="instructor__photo">
\t\t\t\t\t<img src="/bali-paradise-surf/assets/img/photos/26_instruktur-membetulkan-postur-peserta.jpg" alt="Gede Rusmawan - Head Instructor" loading="lazy" decoding="async">
\t\t\t\t</div>
\t\t\t\t<div class="instructor__info">
\t\t\t\t\t<h3>Gede Rusmawan</h3>
\t\t\t\t\t<p class="instructor__role">Founder & Head Instructor</p>
\t\t\t\t\t<p class="instructor__bio">Born and raised on Bali's coastline, Gede has been surfing since age 8 and teaching for over 10 years. His patience, warm smile, and deep understanding of local tides make every student feel safe and confident. As founder of Bali Paradise Surf Lesson, his mission is to share the joy of surfing while supporting his local community.</p>
\t\t\t\t\t<dl class="instructor__stats">
\t\t\t\t\t\t<div><dt>Experience</dt><dd>10+ Years</dd></div>
\t\t\t\t\t\t<div><dt>Specialty</dt><dd>Beginners & Kids</dd></div>
\t\t\t\t\t\t<div><dt>Languages</dt><dd>English, Indonesian, Balinese</dd></div>
\t\t\t\t\t</dl>
\t\t\t\t\t<div class="instructor__social">
\t\t\t\t\t\t<a href="https://www.instagram.com/gede_rusmawan?igsh=MWI2M3BkM3V0dTg0aw==" target="_blank" rel="noopener" aria-label="Gede's Instagram"><svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.3"/></svg></a>
\t\t\t\t\t\t<a href="https://wa.me/6281394265915" target="_blank" rel="noopener" aria-label="Chat with Gede"><svg class="icon icon--whatsapp" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 11.5a8.5 8.5 0 0 1-12.6 7.4L3 20l1.2-4.2A8.5 8.5 0 1 1 20 11.5z"/><path d="M9 8.6c.3 2.5 2.8 5 5.4 5.4l1.3-1.3-2-1-.9.7c-.8-.3-1.7-1.2-2-2l.7-.9-1-2z"/></svg></a>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</article>
\t\t\t<article class="instructor">
\t\t\t\t<div class="instructor__photo">
\t\t\t\t\t<img src="/bali-paradise-surf/assets/img/photos/42_instruktur-memperagakan-teknik-mengayuh.jpg" alt="Ketut - Senior Instructor" loading="lazy" decoding="async">
\t\t\t\t</div>
\t\t\t\t<div class="instructor__info">
\t\t\t\t\t<h3>Ketut Suardana</h3>
\t\t\t\t\t<p class="instructor__role">Senior Instructor</p>
\t\t\t\t\t<p class="instructor__bio">Ketut grew up surfing the waves of Seminyak and Kuta before they became famous. With 8+ years of teaching experience, he specializes in helping adults overcome their fear of the ocean. His calm demeanor and step-by-step approach have helped thousands of first-timers stand up on their boards.</p>
\t\t\t\t\t<dl class="instructor__stats">
\t\t\t\t\t\t<div><dt>Experience</dt><dd>8+ Years</dd></div>
\t\t\t\t\t\t<div><dt>Specialty</dt><dd>Adult Beginners</dd></div>
\t\t\t\t\t\t<div><dt>Languages</dt><dd>English, Indonesian</dd></div>
\t\t\t\t\t</dl>
\t\t\t\t</div>
\t\t\t</article>
\t\t\t<article class="instructor">
\t\t\t\t<div class="instructor__photo">
\t\t\t\t\t<img src="/bali-paradise-surf/assets/img/photos/36_mencoba-berdiri-di-papan-instruktur-memantau.jpg" alt="Wayan - Instructor" loading="lazy" decoding="async">
\t\t\t\t</div>
\t\t\t\t<div class="instructor__info">
\t\t\t\t\t<h3>Wayan Sudiarta</h3>
\t\t\t\t\t<p class="instructor__role">Instructor & Group Specialist</p>
\t\t\t\t\t<p class="instructor__bio">Wayan is the energy of the team! Known for making every lesson feel like a celebration, he's the go-to instructor for group lessons and family sessions. His ability to manage multiple students while keeping everyone laughing and progressing is truly special. Every lesson ends with smiles and unforgettable memories.</p>
\t\t\t\t\t<dl class="instructor__stats">
\t\t\t\t\t\t<div><dt>Experience</dt><dd>6+ Years</dd></div>
\t\t\t\t\t\t<div><dt>Specialty</dt><dd>Groups & Families</dd></div>
\t\t\t\t\t\t<div><dt>Languages</dt><dd>English, Indonesian, Balinese</dd></div>
\t\t\t\t\t</dl>
\t\t\t\t</div>
\t\t\t</article>
\t\t</div>
\t</div>
</section>'''

# Insert after about section (before packages)
about_end = content.find('</section>\n\n<section class="section section--sand" id="packages"')
if about_end == -1:
    about_end = content.find('</section>\r\n\r\n<section class="section section--sand" id="packages"')
if about_end == -1:
    about_end = content.find('</section>\r\n<section class="section section--sand" id="packages"')
if about_end != -1:
    insert_pos = about_end + len('</section>')
    content = content[:insert_pos] + '\n' + instructors_section + '\n' + content[insert_pos:]
    print("SUCCESS: Instructor bios section added")
else:
    print("WARNING: Could not find insertion point for instructors")

# ============================================================
# 2. REPLACE GOOGLE MAPS STATIC IMAGE WITH LIVE IFRAME
# ============================================================

old_maps_img = '<img src="/bali-paradise-surf/assets/img/maps_bali_paradise.jpg" alt="Google Maps showing Bali Paradise Surf Lesson location at Seminyak Beach, Jl. Arjuna, Bali" class="location-map__img" loading="lazy" decoding="async">'
new_maps_iframe = '<iframe title="Map to Bali Paradise Surf Lesson" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="location-map__img" style="border:0;width:100%;aspect-ratio:16/9" src="https://www.google.com/maps?q=Bali%20Paradise%20Surf%20Lesson%2C%20Jl.%20Arjuna%2C%20Seminyak%2C%20Kec.%20Kuta%2C%20Kabupaten%20Badung%2C%20Bali&z=15&output=embed"></iframe>'

if old_maps_img in content:
    content = content.replace(old_maps_img, new_maps_iframe)
    print("SUCCESS: Google Maps iframe embedded")
else:
    print("WARNING: Could not find maps image to replace")

# ============================================================
# 3. REPLACE GALLERY WITH FOLDER PHOTOS
# ============================================================

gallery_photos = [
    ('01_pasangan-berfoto-dengan-papan-selancar-biru.jpg', 'Couple with blue surfboard'),
    ('05_foto-bersama-deretan-papan-selancar.jpg', 'Group photo with surfboards'),
    ('13_dua-peserta-berpose-ceria-di-atas-papan.jpg', 'Two students posing on boards'),
    ('03_anak-anak-berlatih-berdiri-di-atas-papan.jpg', 'Kids learning to stand on board'),
    ('21_foto-berempat-bersama-dua-papan-selancar.jpg', 'Group of four with surfboards'),
    ('30_latihan-tengkurap-dan-mengayuh.jpg', 'Paddling practice on the sand'),
    ('06_latihan-postur-di-bawah-pohon-pandan.jpg', 'Posture training under palm tree'),
    ('04_peserta-berdiri-di-atas-papan-di-ombak.jpg', 'Student standing on board in waves'),
    ('18_berpose-selancar-di-atas-papan-di-pasir.jpg', 'Surf pose on board on sand'),
    ('09_peserta-berselancar-di-antara-buih-ombak.jpg', 'Surfing in the white water'),
    ('37_berfoto-dengan-papan-nama-bali-paradise-surf-lesson.jpg', 'Photo with Bali Paradise Surf Lesson sign'),
    ('49_swafoto-berempat-dengan-pose-peace.jpg', 'Group selfie with peace signs'),
    ('11_meluncur-dengan-papan-tosca.jpg', 'Riding turquoise surfboard'),
    ('33_dua-peserta-berselancar-berdampingan.jpg', 'Two surfers riding side by side'),
    ('23_demonstrasi-teknik-mengayuh-di-pasir.jpg', 'Paddling technique demo on sand'),
    ('41_foto-kelompok-di-tepi-laut.jpg', 'Group photo at waterline'),
    ('15_mempersiapkan-papan-sebelum-pelajaran.jpg', 'Preparing boards before lesson'),
    ('51_papan-nama-berbentuk-papan-selancar-di-pantai.jpg', 'Surfboard-shaped sign on beach'),
]

gallery_html_items = ''
for i, (filename, alt) in enumerate(gallery_photos):
    gallery_html_items += f'''\t\t\t<li>
\t\t\t\t<a href="/bali-paradise-surf/assets/img/photos/{filename}" data-lightbox data-alt="{alt}">
\t\t\t\t\t<img src="/bali-paradise-surf/assets/img/photos/{filename}" alt="{alt}" loading="lazy">
\t\t\t\t</a>
\t\t\t</li>\n'''

# Find and replace the gallery ul content
gallery_start = content.find('<ul class="gallery">')
gallery_end = content.find('</ul>', gallery_start) + len('</ul>')
if gallery_start != -1 and gallery_end > gallery_start:
    content = content[:gallery_start] + '<ul class="gallery">\n' + gallery_html_items + '\t\t</ul>' + content[gallery_end:]
    print("SUCCESS: Gallery updated with folder photos")
else:
    print("WARNING: Could not find gallery section")

# ============================================================
# 4. ADD YOUTUBE VIDEOS SECTION (after gallery, before reviews)
# ============================================================

youtube_section = '''<section class="section" id="videos" aria-labelledby="videos-title">
\t<div class="wrap">
\t\t<header class="section__head">
\t\t\t<h2 id="videos-title">See the stoke in action</h2>
\t\t\t<p>Watch real surf lessons and feel the energy of riding your first wave in Bali.</p>
\t\t</header>
\t\t<div class="videos">
\t\t\t<div class="video">
\t\t\t\t<div class="video__frame">
\t\t\t\t\t<iframe src="https://www.youtube.com/embed/pGcHOmPSWAI" title="Double Six Beach Surf - Bali Paradise Surf Lesson" loading="lazy" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
\t\t\t\t</div>
\t\t\t\t<h3>Double Six Beach Surf</h3>
\t\t\t\t<p>Watch our students catch their first waves on Seminyak's famous Double Six Beach.</p>
\t\t\t</div>
\t\t\t<div class="video">
\t\t\t\t<div class="video__frame">
\t\t\t\t\t<iframe src="https://www.youtube.com/embed/2Xj8tdiWoKY" title="Surfing at Double Six Beach, Bali" loading="lazy" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
\t\t\t\t</div>
\t\t\t\t<h3>Surfing at Double Six Beach</h3>
\t\t\t\t<p>Feel the magic of surfing in Bali — gentle waves, golden sunsets, and pure stoke.</p>
\t\t\t</div>
\t\t</div>
\t\t<div class="videos__cta">
\t\t\t<a class="btn btn--sea" href="https://youtube.com/@choco867?si=Mc1x6t3ugwOUOYVB" target="_blank" rel="noopener">
\t\t\t\t<svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 001.94-2A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>
\t\t\t\tWatch more on YouTube
\t\t\t</a>
\t\t</div>
\t</div>
</section>'''

# Find the lightbox dialog (end of gallery section) 
lightbox_end = content.find('</section>\r\n<section class="section" id="reviews"')
if lightbox_end == -1:
    lightbox_end = content.find('</section>\n<section class="section" id="reviews"')
if lightbox_end != -1:
    insert_pos = lightbox_end + len('</section>')
    content = content[:insert_pos] + '\n' + youtube_section + '\n' + content[insert_pos:]
    print("SUCCESS: YouTube videos section added")
else:
    print("WARNING: Could not find insertion point for YouTube section")

# ============================================================
# 5. ADD NAVIGATION LINKS FOR NEW SECTIONS
# ============================================================

# Add Team and Videos nav links
old_nav = '<li><a href="#gallery">Gallery</a></li>'
new_nav = '<li><a href="#team">Instructors</a></li>\n\t\t\t\t\t\t\t\t\t<li><a href="#gallery">Gallery</a></li>\n\t\t\t\t\t\t\t\t\t<li><a href="#videos">Videos</a></li>'
content = content.replace(old_nav, new_nav, 1)
print("SUCCESS: Navigation links updated")

# ============================================================
# 6. UPDATE FOOTER WITH SOCIAL LINKS
# ============================================================

old_footer_links = '''<ul class="site-footer__links">
\t\t\t<li><a href="https://wa.me/6281394265915?text=Hello%20Bali%20Paradise%20Surf%20Lesson%21%20I%27d%20like%20to%20book%20a%20surf%20lesson." target="_blank" rel="noopener">WhatsApp us</a></li>
\t\t\t\t\t\t\t\t\t\t<li><a href="https://www.instagram.com/gede_rusmawan?igsh=MWI2M3BkM3V0dTg0aw==" target="_blank" rel="noopener">Instagram</a></li>
\t\t\t\t\t\t\t\t</ul>'''

new_footer_links = '''<ul class="site-footer__links">
\t\t\t<li><a href="https://wa.me/6281394265915?text=Hello%20Bali%20Paradise%20Surf%20Lesson%21%20I%27d%20like%20to%20book%20a%20surf%20lesson." target="_blank" rel="noopener">WhatsApp us</a></li>
\t\t\t<li><a href="https://www.instagram.com/gede_rusmawan?igsh=MWI2M3BkM3V0dTg0aw==" target="_blank" rel="noopener">Instagram</a></li>
\t\t\t<li><a href="https://www.facebook.com/share/18jDaFxHcq/?mibextid=wwXIfr" target="_blank" rel="noopener">Facebook</a></li>
\t\t\t<li><a href="https://youtube.com/@choco867?si=Mc1x6t3ugwOUOYVB" target="_blank" rel="noopener">YouTube</a></li>
\t\t</ul>'''

if old_footer_links in content:
    content = content.replace(old_footer_links, new_footer_links)
    print("SUCCESS: Footer social links updated")
else:
    print("WARNING: Could not find footer links to update")

# Write the updated content
with open('index.html', 'w', encoding='utf-8') as f:
    f.write(content)

print("\n=== ALL UPDATES COMPLETE ===")
