// docs/.vuepress/config.js
import { defaultTheme } from "@vuepress/theme-default";
import { defineUserConfig } from "vuepress/cli";
import { viteBundler } from "@vuepress/bundler-vite";
import path from "path";
import fs from "fs";
import Case from "case";
import { markdownTabPlugin } from "@vuepress/plugin-markdown-tab";
import { markdownExtPlugin } from "@vuepress/plugin-markdown-ext";
import { prismjsPlugin } from "@vuepress/plugin-prismjs";
import searchPlugin from "@vuepress/plugin-search";
var __vite_injected_original_dirname = "/mnt/c/Users/leesa/PHPStormProjects/comet-components/docs-site/docs/.vuepress";
var docsDir = path.resolve(__vite_injected_original_dirname, "../");
var config_default = defineUserConfig({
  lang: "en-AU",
  title: "Comet Components",
  description: "A front-end user interface library for PHP-driven websites",
  extend: "@vuepress/theme-default",
  theme: defaultTheme({
    logo: "/comet.png",
    repo: "doubleedesign/comet-components",
    repoLabel: "GitHub",
    navbar: [
      "/",
      {
        text: "Introduction",
        link: "/intro.html"
      },
      {
        text: "Usage",
        link: "/usage/overview.html"
      },
      {
        text: "Development",
        link: "/development/overview.html"
      }
    ],
    sidebar: [
      {
        text: "Introduction",
        link: "/intro.html"
      },
      ...generateSidebar()
    ],
    sidebarDepth: 0,
    // don't put page headings in the sidebar
    markdown: {
      lineNumbers: true
    }
  }),
  plugins: [
    markdownTabPlugin({
      tabs: true
    }),
    markdownExtPlugin({
      gfm: true,
      footnote: true
    }),
    prismjsPlugin({
      theme: "coldark-dark",
      preloadLanguages: ["php", "html", "css", "scss", "js", "json", "bash", "powershell"]
    }),
    searchPlugin({
      searchMaxSuggestions: 10
    })
  ],
  bundler: viteBundler(),
  dest: "../docs",
  base: "/docs/",
  head: [
    ["link", { rel: "icon", type: "image/png", sizes: "32x32", href: "/comet.png" }]
  ]
});
function generateSidebar() {
  const preferredOrder = ["Getting Started", "Usage", "Development", "Technical Deep Dives", "Local Dev Deep Dives", "About"];
  const items = [];
  const files = fs.readdirSync(docsDir, { withFileTypes: true });
  files.forEach((file) => {
    if (file.isDirectory() && file.name !== ".vuepress") {
      const folderName = file.name;
      const readmePath = path.join(docsDir, folderName, "README.md");
      const hasReadme = fs.existsSync(readmePath);
      let sectionTitle = Case.title(folderName).replace("Js", "JS").replace("Php", "PHP");
      if (hasReadme) {
        const extractedTitle = extractTitleFromMarkdown(readmePath);
        if (extractedTitle) {
          sectionTitle = extractedTitle;
        }
      }
      items.push({
        text: sectionTitle,
        link: hasReadme ? `/${folderName}/` : void 0,
        collapsible: true,
        children: getSectionChildren(folderName)
      });
    }
  });
  return items.sort((a, b) => {
    const aIndex = preferredOrder.indexOf(a.text);
    const bIndex = preferredOrder.indexOf(b.text);
    if (aIndex === -1 && bIndex === -1) {
      return a.text.localeCompare(b.text);
    }
    if (aIndex === -1) {
      return 1;
    }
    if (bIndex === -1) {
      return -1;
    }
    return aIndex - bIndex;
  });
}
function getSectionChildren(folderName) {
  const folderPath = path.join(docsDir, folderName);
  if (!fs.existsSync(folderPath) || !fs.statSync(folderPath).isDirectory()) {
    return [];
  }
  const children = [];
  const items = fs.readdirSync(folderPath, { withFileTypes: true });
  const filesWithMetadata = items.filter((item) => item.isFile() && item.name.endsWith(".md")).map((file) => {
    const name = file.name.replace(".md", "");
    if (name !== "README") {
      const filePath = path.join(folderPath, file.name);
      const title = extractTitleFromMarkdown(filePath) ?? Case.title(name);
      const position = extractPagePositionFromMarkdown(filePath);
      return {
        text: title,
        link: `/${folderName}/${name}`,
        position: position !== null ? parseInt(position, 10) : null
      };
    }
    return null;
  }).filter(Boolean);
  filesWithMetadata.sort((a, b) => {
    if (a.position !== null && b.position !== null) {
      return a.position - b.position;
    }
    if (a.position !== null) {
      return -1;
    }
    if (b.position !== null) {
      return 1;
    }
    return a.text.localeCompare(b.text);
  });
  children.push(...filesWithMetadata);
  items.filter((item) => item.isDirectory()).forEach((subfolder) => {
    const subfolderName = subfolder.name;
    const subfolderPath = path.join(folderPath, subfolderName);
    const subfolderItems = [];
    const subfolderFilesWithMetadata = fs.readdirSync(subfolderPath, { withFileTypes: true }).filter((subItem) => subItem.isFile() && subItem.name.endsWith(".md")).map((subFile) => {
      const name = subFile.name.replace(".md", "");
      if (name !== "README") {
        const filePath = path.join(subfolderPath, subFile.name);
        const title = extractTitleFromMarkdown(filePath) ?? Case.title(name);
        const position = extractPagePositionFromMarkdown(filePath);
        return {
          text: title,
          link: `/${folderName}/${subfolderName}/${name}`,
          position: position !== null ? parseInt(position, 10) : null
        };
      }
      return null;
    }).filter(Boolean);
    subfolderFilesWithMetadata.sort((a, b) => {
      if (a.position !== null && b.position !== null) {
        return a.position - b.position;
      }
      if (a.position !== null) {
        return -1;
      }
      if (b.position !== null) {
        return 1;
      }
      return a.text.localeCompare(b.text);
    });
    subfolderItems.push(...subfolderFilesWithMetadata);
    const subfolderReadmePath = path.join(subfolderPath, "README.md");
    let subfolderTitle = Case.title(subfolderName).replace("Js", "JS").replace("Php", "PHP");
    if (fs.existsSync(subfolderReadmePath)) {
      const extractedTitle = extractTitleFromMarkdown(subfolderReadmePath);
      if (extractedTitle) {
        subfolderTitle = extractedTitle;
      }
    }
    if (subfolderItems.length > 0) {
      children.push({
        text: subfolderTitle,
        collapsible: true,
        children: subfolderItems
      });
    }
  });
  return children.sort((a, b) => {
    if (!a.children && b.children) return -1;
    if (a.children && !b.children) return 1;
    if (!a.children && !b.children) {
      return 0;
    }
    return a.text.localeCompare(b.text);
  });
}
function extractTitleFromMarkdown(filePath) {
  try {
    const content = fs.readFileSync(filePath, "utf8");
    const titleMatch = content.match(/^title:\s*(.+)$/m) || content.match(/^#\s+(.+)$/m);
    if (titleMatch && titleMatch[1]) {
      return titleMatch[1].trim();
    }
    return null;
  } catch (error) {
    console.error(`Error reading file ${filePath}:`, error);
    return null;
  }
}
function extractPagePositionFromMarkdown(filePath) {
  try {
    const content = fs.readFileSync(filePath, "utf8");
    const positionMatch = content.match(/^position:\s*(.+)$/m);
    if (positionMatch && positionMatch[1]) {
      return positionMatch[1].trim();
    }
    return null;
  } catch (error) {
    console.error(`Error reading file ${filePath}:`, error);
    return null;
  }
}
export {
  config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiZG9jcy8udnVlcHJlc3MvY29uZmlnLmpzIl0sCiAgInNvdXJjZXNDb250ZW50IjogWyJjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZGlybmFtZSA9IFwiL21udC9jL1VzZXJzL2xlZXNhL1BIUFN0b3JtUHJvamVjdHMvY29tZXQtY29tcG9uZW50cy9kb2NzLXNpdGUvZG9jcy8udnVlcHJlc3NcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIi9tbnQvYy9Vc2Vycy9sZWVzYS9QSFBTdG9ybVByb2plY3RzL2NvbWV0LWNvbXBvbmVudHMvZG9jcy1zaXRlL2RvY3MvLnZ1ZXByZXNzL2NvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vbW50L2MvVXNlcnMvbGVlc2EvUEhQU3Rvcm1Qcm9qZWN0cy9jb21ldC1jb21wb25lbnRzL2RvY3Mtc2l0ZS9kb2NzLy52dWVwcmVzcy9jb25maWcuanNcIjtpbXBvcnQgeyBkZWZhdWx0VGhlbWUgfSBmcm9tICdAdnVlcHJlc3MvdGhlbWUtZGVmYXVsdCc7XHJcbmltcG9ydCB7IGRlZmluZVVzZXJDb25maWcgfSBmcm9tICd2dWVwcmVzcy9jbGknO1xyXG5pbXBvcnQgeyB2aXRlQnVuZGxlciB9IGZyb20gJ0B2dWVwcmVzcy9idW5kbGVyLXZpdGUnO1xyXG5pbXBvcnQgcGF0aCBmcm9tICdwYXRoJztcclxuaW1wb3J0IGZzIGZyb20gJ2ZzJztcclxuaW1wb3J0IENhc2UgZnJvbSAnY2FzZSc7XHJcbmltcG9ydCB7IG1hcmtkb3duVGFiUGx1Z2luIH0gZnJvbSAnQHZ1ZXByZXNzL3BsdWdpbi1tYXJrZG93bi10YWInO1xyXG5pbXBvcnQgeyBtYXJrZG93bkV4dFBsdWdpbiB9IGZyb20gJ0B2dWVwcmVzcy9wbHVnaW4tbWFya2Rvd24tZXh0JztcclxuaW1wb3J0IHsgcHJpc21qc1BsdWdpbiB9IGZyb20gJ0B2dWVwcmVzcy9wbHVnaW4tcHJpc21qcyc7XHJcbmltcG9ydCBzZWFyY2hQbHVnaW4gZnJvbSAnQHZ1ZXByZXNzL3BsdWdpbi1zZWFyY2gnO1xyXG5cclxuY29uc3QgZG9jc0RpciA9IHBhdGgucmVzb2x2ZShfX2Rpcm5hbWUsICcuLi8nKTtcclxuXHJcbmV4cG9ydCBkZWZhdWx0IGRlZmluZVVzZXJDb25maWcoe1xyXG5cdGxhbmc6ICdlbi1BVScsXHJcblxyXG5cdHRpdGxlOiAnQ29tZXQgQ29tcG9uZW50cycsXHJcblx0ZGVzY3JpcHRpb246ICdBIGZyb250LWVuZCB1c2VyIGludGVyZmFjZSBsaWJyYXJ5IGZvciBQSFAtZHJpdmVuIHdlYnNpdGVzJyxcclxuXHJcblx0ZXh0ZW5kOiAnQHZ1ZXByZXNzL3RoZW1lLWRlZmF1bHQnLFxyXG5cdHRoZW1lOiBkZWZhdWx0VGhlbWUoe1xyXG5cdFx0bG9nbzogJy9jb21ldC5wbmcnLFxyXG5cdFx0cmVwbzogJ2RvdWJsZWVkZXNpZ24vY29tZXQtY29tcG9uZW50cycsXHJcblx0XHRyZXBvTGFiZWw6ICdHaXRIdWInLFxyXG5cdFx0bmF2YmFyOiBbXHJcblx0XHRcdCcvJyxcclxuXHRcdFx0e1xyXG5cdFx0XHRcdHRleHQ6ICdJbnRyb2R1Y3Rpb24nLFxyXG5cdFx0XHRcdGxpbms6ICcvaW50cm8uaHRtbCcsXHJcblx0XHRcdH0sXHJcblx0XHRcdHtcclxuXHRcdFx0XHR0ZXh0OiAnVXNhZ2UnLFxyXG5cdFx0XHRcdGxpbms6ICcvdXNhZ2Uvb3ZlcnZpZXcuaHRtbCdcclxuXHRcdFx0fSxcclxuXHRcdFx0e1xyXG5cdFx0XHRcdHRleHQ6ICdEZXZlbG9wbWVudCcsXHJcblx0XHRcdFx0bGluazogJy9kZXZlbG9wbWVudC9vdmVydmlldy5odG1sJyxcclxuXHRcdFx0fVxyXG5cdFx0XSxcclxuXHRcdHNpZGViYXI6IFtcclxuXHRcdFx0e1xyXG5cdFx0XHRcdHRleHQ6ICdJbnRyb2R1Y3Rpb24nLFxyXG5cdFx0XHRcdGxpbms6ICcvaW50cm8uaHRtbCcsXHJcblx0XHRcdH0sXHJcblx0XHRcdC4uLmdlbmVyYXRlU2lkZWJhcigpXHJcblx0XHRdLFxyXG5cdFx0c2lkZWJhckRlcHRoOiAwLCAvLyBkb24ndCBwdXQgcGFnZSBoZWFkaW5ncyBpbiB0aGUgc2lkZWJhclxyXG5cdFx0bWFya2Rvd246IHtcclxuXHRcdFx0bGluZU51bWJlcnM6IHRydWUsXHJcblx0XHR9LFxyXG5cdH0pLFxyXG5cclxuXHRwbHVnaW5zOiBbXHJcblx0XHRtYXJrZG93blRhYlBsdWdpbih7XHJcblx0XHRcdHRhYnM6IHRydWUsXHJcblx0XHR9KSxcclxuXHRcdG1hcmtkb3duRXh0UGx1Z2luKHtcclxuXHRcdFx0Z2ZtOiB0cnVlLFxyXG5cdFx0XHRmb290bm90ZTogdHJ1ZVxyXG5cdFx0fSksXHJcblx0XHRwcmlzbWpzUGx1Z2luKHtcclxuXHRcdFx0dGhlbWU6ICdjb2xkYXJrLWRhcmsnLFxyXG5cdFx0XHRwcmVsb2FkTGFuZ3VhZ2VzOiBbJ3BocCcsICdodG1sJywgJ2NzcycsICdzY3NzJywgJ2pzJywgJ2pzb24nLCAnYmFzaCcsICdwb3dlcnNoZWxsJ10sXHJcblx0XHR9KSxcclxuXHRcdHNlYXJjaFBsdWdpbih7XHJcblx0XHRcdHNlYXJjaE1heFN1Z2dlc3Rpb25zOiAxMFxyXG5cdFx0fSlcclxuXHRdLFxyXG5cclxuXHRidW5kbGVyOiB2aXRlQnVuZGxlcigpLFxyXG5cdGRlc3Q6ICcuLi9kb2NzJyxcclxuXHRiYXNlOiAnL2RvY3MvJyxcclxuXHJcblx0aGVhZDogW1xyXG5cdFx0WydsaW5rJywgeyByZWw6ICdpY29uJywgdHlwZTogJ2ltYWdlL3BuZycsIHNpemVzOiAnMzJ4MzInLCBocmVmOiAnL2NvbWV0LnBuZycgfV0sXHJcblx0XSxcclxufSk7XHJcblxyXG4vLyBHZW5lcmF0ZSBzdHJ1Y3R1cmVkIHNpZGViYXIgaXRlbXNcclxuZnVuY3Rpb24gZ2VuZXJhdGVTaWRlYmFyKCkge1xyXG5cdGNvbnN0IHByZWZlcnJlZE9yZGVyID0gWydHZXR0aW5nIFN0YXJ0ZWQnLCAnVXNhZ2UnLCAnRGV2ZWxvcG1lbnQnLCAnVGVjaG5pY2FsIERlZXAgRGl2ZXMnLCAnTG9jYWwgRGV2IERlZXAgRGl2ZXMnLCAnQWJvdXQnXTtcclxuXHRjb25zdCBpdGVtcyA9IFtdO1xyXG5cdGNvbnN0IGZpbGVzID0gZnMucmVhZGRpclN5bmMoZG9jc0RpciwgeyB3aXRoRmlsZVR5cGVzOiB0cnVlIH0pO1xyXG5cclxuXHRmaWxlcy5mb3JFYWNoKChmaWxlKSA9PiB7XHJcblx0XHRpZiAoZmlsZS5pc0RpcmVjdG9yeSgpICYmIGZpbGUubmFtZSAhPT0gJy52dWVwcmVzcycpIHtcclxuXHRcdFx0Y29uc3QgZm9sZGVyTmFtZSA9IGZpbGUubmFtZTtcclxuXHRcdFx0Ly8gQ2hlY2sgaWYgdGhlcmUncyBhIFJFQURNRS5tZCBmaWxlIGZvciB0aGUgbWFpbiBzZWN0aW9uXHJcblx0XHRcdGNvbnN0IHJlYWRtZVBhdGggPSBwYXRoLmpvaW4oZG9jc0RpciwgZm9sZGVyTmFtZSwgJ1JFQURNRS5tZCcpO1xyXG5cdFx0XHRjb25zdCBoYXNSZWFkbWUgPSBmcy5leGlzdHNTeW5jKHJlYWRtZVBhdGgpO1xyXG5cclxuXHRcdFx0Ly8gVHJ5IHRvIGV4dHJhY3QgdGl0bGUgZnJvbSBSRUFETUUgaWYgaXQgZXhpc3RzXHJcblx0XHRcdGxldCBzZWN0aW9uVGl0bGUgPSBDYXNlLnRpdGxlKGZvbGRlck5hbWUpLnJlcGxhY2UoJ0pzJywgJ0pTJykucmVwbGFjZSgnUGhwJywgJ1BIUCcpO1xyXG5cdFx0XHRpZiAoaGFzUmVhZG1lKSB7XHJcblx0XHRcdFx0Y29uc3QgZXh0cmFjdGVkVGl0bGUgPSBleHRyYWN0VGl0bGVGcm9tTWFya2Rvd24ocmVhZG1lUGF0aCk7XHJcblx0XHRcdFx0aWYgKGV4dHJhY3RlZFRpdGxlKSB7XHJcblx0XHRcdFx0XHRzZWN0aW9uVGl0bGUgPSBleHRyYWN0ZWRUaXRsZTtcclxuXHRcdFx0XHR9XHJcblx0XHRcdH1cclxuXHJcblx0XHRcdGl0ZW1zLnB1c2goe1xyXG5cdFx0XHRcdHRleHQ6IHNlY3Rpb25UaXRsZSxcclxuXHRcdFx0XHRsaW5rOiBoYXNSZWFkbWUgPyBgLyR7Zm9sZGVyTmFtZX0vYCA6IHVuZGVmaW5lZCxcclxuXHRcdFx0XHRjb2xsYXBzaWJsZTogdHJ1ZSxcclxuXHRcdFx0XHRjaGlsZHJlbjogZ2V0U2VjdGlvbkNoaWxkcmVuKGZvbGRlck5hbWUpXHJcblx0XHRcdH0pO1xyXG5cdFx0fVxyXG5cdH0pO1xyXG5cclxuXHQvLyBTb3J0IGFjY29yZGluZyB0byBwcmVmZXJyZWQgb3JkZXJcclxuXHRyZXR1cm4gaXRlbXMuc29ydCgoYSwgYikgPT4ge1xyXG5cdFx0Y29uc3QgYUluZGV4ID0gcHJlZmVycmVkT3JkZXIuaW5kZXhPZihhLnRleHQpO1xyXG5cdFx0Y29uc3QgYkluZGV4ID0gcHJlZmVycmVkT3JkZXIuaW5kZXhPZihiLnRleHQpO1xyXG5cdFx0aWYgKGFJbmRleCA9PT0gLTEgJiYgYkluZGV4ID09PSAtMSkge1xyXG5cdFx0XHRyZXR1cm4gYS50ZXh0LmxvY2FsZUNvbXBhcmUoYi50ZXh0KTtcclxuXHRcdH1cclxuXHRcdGlmIChhSW5kZXggPT09IC0xKSB7XHJcblx0XHRcdHJldHVybiAxO1xyXG5cdFx0fVxyXG5cdFx0aWYgKGJJbmRleCA9PT0gLTEpIHtcclxuXHRcdFx0cmV0dXJuIC0xO1xyXG5cdFx0fVxyXG5cclxuXHRcdHJldHVybiBhSW5kZXggLSBiSW5kZXg7XHJcblx0fSk7XHJcbn1cclxuXHJcbi8vIEdldCB0aGUgY2hpbGQgcGFnZXMgZm9yIGEgc3BlY2lmaWMgc2VjdGlvbiBmb2xkZXIsIGluY2x1ZGluZyBuZXN0ZWQgc3ViZm9sZGVyc1xyXG5mdW5jdGlvbiBnZXRTZWN0aW9uQ2hpbGRyZW4oZm9sZGVyTmFtZSkge1xyXG5cdGNvbnN0IGZvbGRlclBhdGggPSBwYXRoLmpvaW4oZG9jc0RpciwgZm9sZGVyTmFtZSk7XHJcblx0Ly8gQ2hlY2sgaWYgdGhlIGZvbGRlciBleGlzdHNcclxuXHRpZiAoIWZzLmV4aXN0c1N5bmMoZm9sZGVyUGF0aCkgfHwgIWZzLnN0YXRTeW5jKGZvbGRlclBhdGgpLmlzRGlyZWN0b3J5KCkpIHtcclxuXHRcdHJldHVybiBbXTtcclxuXHR9XHJcblxyXG5cdGNvbnN0IGNoaWxkcmVuID0gW107XHJcblxyXG5cdC8vIEdldCBhbGwgaXRlbXMgaW4gdGhlIGRpcmVjdG9yeVxyXG5cdGNvbnN0IGl0ZW1zID0gZnMucmVhZGRpclN5bmMoZm9sZGVyUGF0aCwgeyB3aXRoRmlsZVR5cGVzOiB0cnVlIH0pO1xyXG5cclxuXHQvLyBQcm9jZXNzIGZpbGVzIGZpcnN0XHJcblx0Y29uc3QgZmlsZXNXaXRoTWV0YWRhdGEgPSBpdGVtc1xyXG5cdFx0LmZpbHRlcigoaXRlbSkgPT4gaXRlbS5pc0ZpbGUoKSAmJiBpdGVtLm5hbWUuZW5kc1dpdGgoJy5tZCcpKVxyXG5cdFx0Lm1hcCgoZmlsZSkgPT4ge1xyXG5cdFx0XHRjb25zdCBuYW1lID0gZmlsZS5uYW1lLnJlcGxhY2UoJy5tZCcsICcnKTtcclxuXHRcdFx0aWYgKG5hbWUgIT09ICdSRUFETUUnKSB7XHJcblx0XHRcdFx0Y29uc3QgZmlsZVBhdGggPSBwYXRoLmpvaW4oZm9sZGVyUGF0aCwgZmlsZS5uYW1lKTtcclxuXHRcdFx0XHRjb25zdCB0aXRsZSA9IGV4dHJhY3RUaXRsZUZyb21NYXJrZG93bihmaWxlUGF0aCkgPz8gQ2FzZS50aXRsZShuYW1lKTtcclxuXHRcdFx0XHRjb25zdCBwb3NpdGlvbiA9IGV4dHJhY3RQYWdlUG9zaXRpb25Gcm9tTWFya2Rvd24oZmlsZVBhdGgpO1xyXG5cclxuXHRcdFx0XHRyZXR1cm4ge1xyXG5cdFx0XHRcdFx0dGV4dDogdGl0bGUsXHJcblx0XHRcdFx0XHRsaW5rOiBgLyR7Zm9sZGVyTmFtZX0vJHtuYW1lfWAsXHJcblx0XHRcdFx0XHRwb3NpdGlvbjogcG9zaXRpb24gIT09IG51bGwgPyBwYXJzZUludChwb3NpdGlvbiwgMTApIDogbnVsbFxyXG5cdFx0XHRcdH07XHJcblx0XHRcdH1cclxuXHJcblx0XHRcdHJldHVybiBudWxsO1xyXG5cdFx0fSlcclxuXHRcdC5maWx0ZXIoQm9vbGVhbik7XHJcblxyXG5cdC8vIFNvcnQgZmlsZXMgYnkgcG9zaXRpb24gZmlyc3QsIHRoZW4gYnkgdGl0bGVcclxuXHRmaWxlc1dpdGhNZXRhZGF0YS5zb3J0KChhLCBiKSA9PiB7XHJcblx0XHQvLyBJZiBib3RoIGhhdmUgcG9zaXRpb25zLCBzb3J0IG51bWVyaWNhbGx5XHJcblx0XHRpZiAoYS5wb3NpdGlvbiAhPT0gbnVsbCAmJiBiLnBvc2l0aW9uICE9PSBudWxsKSB7XHJcblx0XHRcdHJldHVybiBhLnBvc2l0aW9uIC0gYi5wb3NpdGlvbjtcclxuXHRcdH1cclxuXHRcdC8vIElmIG9ubHkgYSBoYXMgcG9zaXRpb24sIGl0IGNvbWVzIGZpcnN0XHJcblx0XHRpZiAoYS5wb3NpdGlvbiAhPT0gbnVsbCkge1xyXG5cdFx0XHRyZXR1cm4gLTE7XHJcblx0XHR9XHJcblx0XHQvLyBJZiBvbmx5IGIgaGFzIHBvc2l0aW9uLCBpdCBjb21lcyBmaXJzdFxyXG5cdFx0aWYgKGIucG9zaXRpb24gIT09IG51bGwpIHtcclxuXHRcdFx0cmV0dXJuIDE7XHJcblx0XHR9XHJcblxyXG5cdFx0Ly8gSWYgbmVpdGhlciBoYXMgcG9zaXRpb24sIHNvcnQgYWxwaGFiZXRpY2FsbHlcclxuXHRcdHJldHVybiBhLnRleHQubG9jYWxlQ29tcGFyZShiLnRleHQpO1xyXG5cdH0pO1xyXG5cclxuXHQvLyBBZGQgdGhlIHNvcnRlZCBmaWxlcyB0byBjaGlsZHJlblxyXG5cdGNoaWxkcmVuLnB1c2goLi4uZmlsZXNXaXRoTWV0YWRhdGEpO1xyXG5cclxuXHQvLyBQcm9jZXNzIHN1YmZvbGRlcnNcclxuXHRpdGVtc1xyXG5cdFx0LmZpbHRlcigoaXRlbSkgPT4gaXRlbS5pc0RpcmVjdG9yeSgpKVxyXG5cdFx0LmZvckVhY2goKHN1YmZvbGRlcikgPT4ge1xyXG5cdFx0XHRjb25zdCBzdWJmb2xkZXJOYW1lID0gc3ViZm9sZGVyLm5hbWU7XHJcblx0XHRcdGNvbnN0IHN1YmZvbGRlclBhdGggPSBwYXRoLmpvaW4oZm9sZGVyUGF0aCwgc3ViZm9sZGVyTmFtZSk7XHJcblx0XHRcdGNvbnN0IHN1YmZvbGRlckl0ZW1zID0gW107XHJcblxyXG5cdFx0XHQvLyBHZXQgbWFya2Rvd24gZmlsZXMgaW4gdGhlIHN1YmZvbGRlclxyXG5cdFx0XHRjb25zdCBzdWJmb2xkZXJGaWxlc1dpdGhNZXRhZGF0YSA9IGZzLnJlYWRkaXJTeW5jKHN1YmZvbGRlclBhdGgsIHsgd2l0aEZpbGVUeXBlczogdHJ1ZSB9KVxyXG5cdFx0XHRcdC5maWx0ZXIoKHN1Ykl0ZW0pID0+IHN1Ykl0ZW0uaXNGaWxlKCkgJiYgc3ViSXRlbS5uYW1lLmVuZHNXaXRoKCcubWQnKSlcclxuXHRcdFx0XHQubWFwKChzdWJGaWxlKSA9PiB7XHJcblx0XHRcdFx0XHRjb25zdCBuYW1lID0gc3ViRmlsZS5uYW1lLnJlcGxhY2UoJy5tZCcsICcnKTtcclxuXHRcdFx0XHRcdGlmIChuYW1lICE9PSAnUkVBRE1FJykge1xyXG5cdFx0XHRcdFx0XHRjb25zdCBmaWxlUGF0aCA9IHBhdGguam9pbihzdWJmb2xkZXJQYXRoLCBzdWJGaWxlLm5hbWUpO1xyXG5cdFx0XHRcdFx0XHRjb25zdCB0aXRsZSA9IGV4dHJhY3RUaXRsZUZyb21NYXJrZG93bihmaWxlUGF0aCkgPz8gQ2FzZS50aXRsZShuYW1lKTtcclxuXHRcdFx0XHRcdFx0Y29uc3QgcG9zaXRpb24gPSBleHRyYWN0UGFnZVBvc2l0aW9uRnJvbU1hcmtkb3duKGZpbGVQYXRoKTtcclxuXHJcblx0XHRcdFx0XHRcdHJldHVybiB7XHJcblx0XHRcdFx0XHRcdFx0dGV4dDogdGl0bGUsXHJcblx0XHRcdFx0XHRcdFx0bGluazogYC8ke2ZvbGRlck5hbWV9LyR7c3ViZm9sZGVyTmFtZX0vJHtuYW1lfWAsXHJcblx0XHRcdFx0XHRcdFx0cG9zaXRpb246IHBvc2l0aW9uICE9PSBudWxsID8gcGFyc2VJbnQocG9zaXRpb24sIDEwKSA6IG51bGxcclxuXHRcdFx0XHRcdFx0fTtcclxuXHRcdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0XHRyZXR1cm4gbnVsbDtcclxuXHRcdFx0XHR9KVxyXG5cdFx0XHRcdC5maWx0ZXIoQm9vbGVhbik7XHJcblxyXG5cdFx0XHQvLyBTb3J0IHN1YmZvbGRlciBmaWxlcyBieSBwb3NpdGlvbiBmaXJzdCwgdGhlbiBieSB0aXRsZVxyXG5cdFx0XHRzdWJmb2xkZXJGaWxlc1dpdGhNZXRhZGF0YS5zb3J0KChhLCBiKSA9PiB7XHJcblx0XHRcdFx0Ly8gSWYgYm90aCBoYXZlIHBvc2l0aW9ucywgc29ydCBudW1lcmljYWxseVxyXG5cdFx0XHRcdGlmIChhLnBvc2l0aW9uICE9PSBudWxsICYmIGIucG9zaXRpb24gIT09IG51bGwpIHtcclxuXHRcdFx0XHRcdHJldHVybiBhLnBvc2l0aW9uIC0gYi5wb3NpdGlvbjtcclxuXHRcdFx0XHR9XHJcblx0XHRcdFx0Ly8gSWYgb25seSBhIGhhcyBwb3NpdGlvbiwgaXQgY29tZXMgZmlyc3RcclxuXHRcdFx0XHRpZiAoYS5wb3NpdGlvbiAhPT0gbnVsbCkge1xyXG5cdFx0XHRcdFx0cmV0dXJuIC0xO1xyXG5cdFx0XHRcdH1cclxuXHRcdFx0XHQvLyBJZiBvbmx5IGIgaGFzIHBvc2l0aW9uLCBpdCBjb21lcyBmaXJzdFxyXG5cdFx0XHRcdGlmIChiLnBvc2l0aW9uICE9PSBudWxsKSB7XHJcblx0XHRcdFx0XHRyZXR1cm4gMTtcclxuXHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdC8vIElmIG5laXRoZXIgaGFzIHBvc2l0aW9uLCBzb3J0IGFscGhhYmV0aWNhbGx5XHJcblx0XHRcdFx0cmV0dXJuIGEudGV4dC5sb2NhbGVDb21wYXJlKGIudGV4dCk7XHJcblx0XHRcdH0pO1xyXG5cclxuXHRcdFx0Ly8gQWRkIHRoZSBzb3J0ZWQgc3ViZm9sZGVyIGZpbGVzXHJcblx0XHRcdHN1YmZvbGRlckl0ZW1zLnB1c2goLi4uc3ViZm9sZGVyRmlsZXNXaXRoTWV0YWRhdGEpO1xyXG5cclxuXHRcdFx0Ly8gQ2hlY2sgaWYgc3ViZm9sZGVyIGhhcyBSRUFETUUgZm9yIGl0cyB0aXRsZVxyXG5cdFx0XHRjb25zdCBzdWJmb2xkZXJSZWFkbWVQYXRoID0gcGF0aC5qb2luKHN1YmZvbGRlclBhdGgsICdSRUFETUUubWQnKTtcclxuXHRcdFx0bGV0IHN1YmZvbGRlclRpdGxlID0gQ2FzZS50aXRsZShzdWJmb2xkZXJOYW1lKVxyXG5cdFx0XHRcdC5yZXBsYWNlKCdKcycsICdKUycpXHJcblx0XHRcdFx0LnJlcGxhY2UoJ1BocCcsICdQSFAnKTtcclxuXHJcblx0XHRcdGlmIChmcy5leGlzdHNTeW5jKHN1YmZvbGRlclJlYWRtZVBhdGgpKSB7XHJcblx0XHRcdFx0Y29uc3QgZXh0cmFjdGVkVGl0bGUgPSBleHRyYWN0VGl0bGVGcm9tTWFya2Rvd24oc3ViZm9sZGVyUmVhZG1lUGF0aCk7XHJcblx0XHRcdFx0aWYgKGV4dHJhY3RlZFRpdGxlKSB7XHJcblx0XHRcdFx0XHRzdWJmb2xkZXJUaXRsZSA9IGV4dHJhY3RlZFRpdGxlO1xyXG5cdFx0XHRcdH1cclxuXHRcdFx0fVxyXG5cclxuXHRcdFx0Ly8gQWRkIHRoZSBzdWJmb2xkZXIgd2l0aCBpdHMgY2hpbGRyZW4gaWYgaXQgaGFzIGFueSBjb250ZW50XHJcblx0XHRcdGlmIChzdWJmb2xkZXJJdGVtcy5sZW5ndGggPiAwKSB7XHJcblx0XHRcdFx0Y2hpbGRyZW4ucHVzaCh7XHJcblx0XHRcdFx0XHR0ZXh0OiBzdWJmb2xkZXJUaXRsZSxcclxuXHRcdFx0XHRcdGNvbGxhcHNpYmxlOiB0cnVlLFxyXG5cdFx0XHRcdFx0Y2hpbGRyZW46IHN1YmZvbGRlckl0ZW1zXHJcblx0XHRcdFx0fSk7XHJcblx0XHRcdH1cclxuXHRcdH0pO1xyXG5cclxuXHQvLyBTb3J0IHRoZSB0b3AtbGV2ZWwgaXRlbXMgLSBmb2xkZXJzIHN0aWxsIGNvbWUgYWZ0ZXIgZmlsZXNcclxuXHRyZXR1cm4gY2hpbGRyZW4uc29ydCgoYSwgYikgPT4ge1xyXG5cdFx0Ly8gSWYgaXQncyBhIGZpbGUgdnMgc3ViZm9sZGVyLCBmaWxlcyBjb21lIGZpcnN0XHJcblx0XHRpZiAoIWEuY2hpbGRyZW4gJiYgYi5jaGlsZHJlbikgcmV0dXJuIC0xO1xyXG5cdFx0aWYgKGEuY2hpbGRyZW4gJiYgIWIuY2hpbGRyZW4pIHJldHVybiAxO1xyXG5cclxuXHRcdC8vIElmIGJvdGggYXJlIGZpbGVzLCB0aGV5J3ZlIGFscmVhZHkgYmVlbiBzb3J0ZWQgYnkgcG9zaXRpb24gYW5kIHRpdGxlXHJcblx0XHRpZiAoIWEuY2hpbGRyZW4gJiYgIWIuY2hpbGRyZW4pIHtcclxuXHRcdFx0cmV0dXJuIDA7IC8vIEtlZXAgdGhlIGV4aXN0aW5nIG9yZGVyIGZyb20gb3VyIHByZXZpb3VzIHNvcnRcclxuXHRcdH1cclxuXHJcblx0XHQvLyBJZiBib3RoIGFyZSBmb2xkZXJzLCBzb3J0IGFscGhhYmV0aWNhbGx5XHJcblx0XHRyZXR1cm4gYS50ZXh0LmxvY2FsZUNvbXBhcmUoYi50ZXh0KTtcclxuXHR9KTtcclxufVxyXG5cclxuLy8gRnVuY3Rpb24gdG8gZXh0cmFjdCB0aXRsZSBmcm9tIG1hcmtkb3duIGZpbGVcclxuZnVuY3Rpb24gZXh0cmFjdFRpdGxlRnJvbU1hcmtkb3duKGZpbGVQYXRoKSB7XHJcblx0dHJ5IHtcclxuXHRcdGNvbnN0IGNvbnRlbnQgPSBmcy5yZWFkRmlsZVN5bmMoZmlsZVBhdGgsICd1dGY4Jyk7XHJcblxyXG5cdFx0Ly8gTG9vayBmb3IgdGhlIGZpcnN0IGhlYWRpbmcgaW4gdGhlIGZpbGVcclxuXHRcdGNvbnN0IHRpdGxlTWF0Y2ggPVxyXG5cdFx0XHRjb250ZW50Lm1hdGNoKC9edGl0bGU6XFxzKiguKykkL20pIC8vIE1hdGNoIFlBTUwgZnJvbnRtYXR0ZXIgdGl0bGU6IFRpdGxlXHJcblx0XHRcdHx8IGNvbnRlbnQubWF0Y2goL14jXFxzKyguKykkL20pOyAvLyBNYXRjaCAjIFRpdGxlXHJcblxyXG5cdFx0aWYgKHRpdGxlTWF0Y2ggJiYgdGl0bGVNYXRjaFsxXSkge1xyXG5cdFx0XHRyZXR1cm4gdGl0bGVNYXRjaFsxXS50cmltKCk7XHJcblx0XHR9XHJcblxyXG5cdFx0cmV0dXJuIG51bGw7XHJcblx0fVxyXG5cdGNhdGNoIChlcnJvcikge1xyXG5cdFx0Y29uc29sZS5lcnJvcihgRXJyb3IgcmVhZGluZyBmaWxlICR7ZmlsZVBhdGh9OmAsIGVycm9yKTtcclxuXHJcblx0XHRyZXR1cm4gbnVsbDtcclxuXHR9XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGV4dHJhY3RQYWdlUG9zaXRpb25Gcm9tTWFya2Rvd24oZmlsZVBhdGgpIHtcclxuXHR0cnkge1xyXG5cdFx0Y29uc3QgY29udGVudCA9IGZzLnJlYWRGaWxlU3luYyhmaWxlUGF0aCwgJ3V0ZjgnKTtcclxuXHJcblx0XHRjb25zdCBwb3NpdGlvbk1hdGNoID1cclxuXHRcdFx0Y29udGVudC5tYXRjaCgvXnBvc2l0aW9uOlxccyooLispJC9tKTsgLy8gTWF0Y2ggWUFNTCBmcm9udG1hdHRlciBwb3NpdGlvbjogbnVtYmVyXHJcblxyXG5cdFx0aWYgKHBvc2l0aW9uTWF0Y2ggJiYgcG9zaXRpb25NYXRjaFsxXSkge1xyXG5cdFx0XHRyZXR1cm4gcG9zaXRpb25NYXRjaFsxXS50cmltKCk7XHJcblx0XHR9XHJcblxyXG5cdFx0cmV0dXJuIG51bGw7XHJcblx0fVxyXG5cdGNhdGNoIChlcnJvcikge1xyXG5cdFx0Y29uc29sZS5lcnJvcihgRXJyb3IgcmVhZGluZyBmaWxlICR7ZmlsZVBhdGh9OmAsIGVycm9yKTtcclxuXHJcblx0XHRyZXR1cm4gbnVsbDtcclxuXHR9XHJcbn1cclxuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUErWSxTQUFTLG9CQUFvQjtBQUM1YSxTQUFTLHdCQUF3QjtBQUNqQyxTQUFTLG1CQUFtQjtBQUM1QixPQUFPLFVBQVU7QUFDakIsT0FBTyxRQUFRO0FBQ2YsT0FBTyxVQUFVO0FBQ2pCLFNBQVMseUJBQXlCO0FBQ2xDLFNBQVMseUJBQXlCO0FBQ2xDLFNBQVMscUJBQXFCO0FBQzlCLE9BQU8sa0JBQWtCO0FBVHpCLElBQU0sbUNBQW1DO0FBV3pDLElB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
